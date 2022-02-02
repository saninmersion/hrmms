<template>
    <div class="flex justify-between w-full">
        <p v-if="total" class="pl-0 py-2 rounded my-2 text-xs xs:text-sm text-gray-900">
            {{ (current_page - 1) * per_page + 1 }}-{{
                total > (current_page * per_page) ? current_page * per_page : total
            }} / {{ total }}
        </p>

        <ul v-if="total_pages > 1" class="flex pl-0 list-none rounded my-2">
            <li :class="{'bg-gray-200': isInFirstPage}"
                class="leading-tight bg-white border border-gray-300 border-r-0 ml-0 rounded-l hover:bg-primary-light">
                <button :class="{'cursor-not-allowed': isInFirstPage}"
                        :disabled="isInFirstPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoFirst">
                    &laquo;
                </button>
            </li>

            <li :class="{'bg-gray-200': isInFirstPage}"
                class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-primary-light">
                <button :class="{'cursor-not-allowed': isInFirstPage}"
                        :disabled="isInFirstPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoPrevious">
                    &lsaquo;
                </button>
            </li>

            <template v-if="showDots('left')">
                <li :class="{'bg-primary': isInFirstPage}"
                    class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-primary-light">
                    <button :class="{'cursor-not-allowed': isInFirstPage}"
                            :disabled="isInFirstPage"
                            class="py-2 px-3"
                            type="button"
                            @click="gotoPageNumber(1)">
                        1
                    </button>
                </li>

                <li class="leading-tight bg-white border border-gray-300 border-r-0 bg-gray-200 hover:bg-gray-200 hover:bg-primary-light">
                    <button :disabled="true"
                            class="py-2 px-3 cursor-not-allowed"
                            type="button">
                        ...
                    </button>
                </li>
            </template>

            <li v-for="(page, index) in pages"
                :key="`pages_${index}`"
                :class="{'bg-primary': page === currentPage}"
                class="leading-tight border border-gray-300 border-r-0 hover:bg-primary-light">
                <button :class="{'cursor-not-allowed bg-primary-500 text-white': page === currentPage}"
                        :disabled="page === currentPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoPageNumber(page)">
                    {{ page }}
                </button>
            </li>

            <template v-if="showDots('right')">
                <li class="leading-tight bg-white border border-gray-300 border-r-0 bg-gray-200 hover:bg-gray-200 hover:bg-primary-light">
                    <button :disabled="true"
                            class="py-2 px-3 cursor-not-allowed"
                            type="button">
                        ...
                    </button>
                </li>

                <li :class="{'bg-primary': isInLastPage}"
                    class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-primary-light">
                    <button :class="{'cursor-not-allowed': isInLastPage}"
                            :disabled="isInLastPage"
                            class="py-2 px-3"
                            type="button"
                            @click="gotoPageNumber(total_pages)">
                        {{ total_pages }}
                    </button>
                </li>
            </template>

            <li :class="{'bg-gray-200': isInLastPage}"
                class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-primary-light">
                <button :class="{'cursor-not-allowed': isInLastPage}"
                        :disabled="isInLastPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoNext">
                    &rsaquo;
                </button>
            </li>

            <li :class="{'bg-gray-200': isInLastPage}"
                class="leading-tight bg-white border border-gray-300 border-r-0 rounded-r border-r hover:bg-primary-light">
                <button :class="{'cursor-not-allowed': isInLastPage}"
                        :disabled="isInLastPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoLast">
                    &raquo;
                </button>
            </li>
        </ul>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "Pagination",

        props: {
            currentPage: { type: Number, required: true, default: 1 },
            pagination: { type: Object, required: true, default: () => ({}) },
            maxVisibleButtons: { type: Number, required: false, default: 5 },
        },

        data: () => ({
            current_page: 1,
            per_page: 10,
            total: 0,
            total_pages: 0,
        }),

        watch: {
            pagination: {
                handler(pagination) {
                    this.current_page = pagination.current_page || 10
                    this.per_page = pagination.per_page || 10
                    this.total = pagination.total || 0
                    this.total_pages = pagination.total_pages || 0
                },
                immediate: true,
            },
        },

        computed: {
            isInFirstPage() {
                return this.currentPage === 1
            },

            isInLastPage() {
                return this.currentPage === this.total_pages
            },

            pages() {
                const range = []

                for (let i = this.startPage; i <= this.endPage; i += 1) {
                    if (i > 0) {
                        range.push(i)
                    }
                }

                return range
            },

            startPage() {
                if (this.currentPage === 1) {
                    return 1
                }

                if (this.currentPage === this.total_pages) {
                    return this.total_pages - this.maxVisibleButtons + 1
                }

                return this.currentPage - 1
            },

            endPage() {
                return Math.min(this.startPage + this.maxVisibleButtons - 1, this.total_pages)
            },
        },

        methods: {
            showDots(position = "left") {
                const number = position === "left" ? 1 : this.total_pages
                const nextNumber = position === "left" ? 2 : this.total_pages - 1

                return !this.pages.includes(number) || !this.pages.includes(nextNumber)
            },

            gotoFirst() {
                this.gotoPageNumber(1)
            },

            gotoLast() {
                this.gotoPageNumber(this.total_pages)
            },

            gotoPrevious() {
                this.gotoPageNumber(this.currentPage - 1)
            },

            gotoNext() {
                this.gotoPageNumber(this.currentPage + 1)
            },

            gotoPageNumber(pageNumber) {
                this.$emit("pagechanged", pageNumber <= 0 ? 1 : pageNumber)
            },
        },
    }
</script>
