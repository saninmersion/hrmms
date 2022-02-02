<template>
    <div class="flex flex-wrap gap-4 justify-between items-center">
        <p v-if="total" class="text-base-3">
            {{ numberTrans((currentPage - 1) * per_page + 1) }}-{{
                numberTrans(total > (currentPage * per_page) ? currentPage * per_page : total)
            }} / {{ numberTrans(total) }}
        </p>

        <ul v-if="total_pages > 1" class="flex flex-wrap list-none">
            <li :class="{'bg-grey-1': isInFirstPage}"
                class="leading-tight bg-white border border-r-0 ml-0 rounded-l hover:bg-grey-3">
                <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInFirstPage}"
                        :disabled="isInFirstPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoFirst">
                    &laquo;
                </button>
            </li>

            <li :class="{'bg-grey-1': isInFirstPage}"
                class="leading-tight bg-white border border-r-0 hover:bg-grey-3">
                <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInFirstPage}"
                        :disabled="isInFirstPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoPrevious">
                    &lsaquo;
                </button>
            </li>

            <template v-if="showDots('left')">
                <li :class="{'bg-primary-500 text-white': isInFirstPage}"
                    class="leading-tight bg-white border border-r-0 hover:bg-grey-3">
                    <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInFirstPage}"
                            :disabled="isInFirstPage"
                            class="py-2 px-3"
                            type="button"
                            @click="gotoPageNumber(1)">
                        1
                    </button>
                </li>

                <li class="leading-tight bg-white border border-r-0 ">
                    <button :disabled="true"
                            class="py-2 px-3 cursor-not-allowed bg-grey-3  opacity-70"
                            type="button">
                        ...
                    </button>
                </li>
            </template>

            <li v-for="(page, index) in pages"
                :key="`pages_${index}`"
                :class="page === currentPage ? 'bg-primary-500 text-white' : 'hover:bg-grey-3'"
                class="leading-tight border border-r-0">
                <button :class="{'cursor-not-allowed': page === currentPage}"
                        :disabled="page === currentPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoPageNumber(page)">
                    {{ page }}
                </button>
            </li>

            <template v-if="showDots('right')">
                <li class="leading-tight bg-white border border-r-0 bg-grey-1  hover:bg-grey-3">
                    <button :disabled="true"
                            class="py-2 px-3 cursor-not-allowed bg-grey-3 opacity-70"
                            type="button">
                        ...
                    </button>
                </li>

                <li :class="{'bg-primary-500 text-white': isInLastPage}"
                    class="leading-tight bg-white border border-r-0 hover:bg-grey-3">
                    <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInLastPage}"
                            :disabled="isInLastPage"
                            class="py-2 px-3"
                            type="button"
                            @click="gotoPageNumber(total_pages)">
                        {{ total_pages }}
                    </button>
                </li>
            </template>

            <li :class="{'bg-grey-1': isInLastPage}"
                class="leading-tight bg-white border border-r-0 hover:bg-grey-3">
                <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInLastPage}"
                        :disabled="isInLastPage"
                        class="py-2 px-3"
                        type="button"
                        @click="gotoNext">
                    &rsaquo;
                </button>
            </li>

            <li :class="{'bg-grey-1': isInLastPage}"
                class="leading-tight bg-white border border-r-0 rounded-r border-r hover:bg-grey-3">
                <button :class="{'cursor-not-allowed bg-grey-3 opacity-70': isInLastPage}"
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
            per_page: 10,
            total: 0,
            total_pages: 0,
        }),

        watch: {
            pagination: {
                handler(pagination) {
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

<style scoped>
    button:focus {
        outline: none;
    }
</style>
