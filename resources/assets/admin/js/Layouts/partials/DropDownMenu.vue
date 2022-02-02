<template>
    <div class="relative">
        <div @click="open = !open">
            <slot name="trigger"/>
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"/>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-show="open"
                 class="dropdown-menu absolute z-50 mt-2 rounded-md shadow-lg w-40"
                 :class="[alignmentClass]"
                 style="display: none;"
                 @click="open = false">
                <div class="rounded-md shadow-xs py-1 bg-white">
                    <slot name="content"/>
                </div>
            </div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "DropDownMenu",

        props: {
            align: { type: String, required: false, default: "left" },
        },

        data: () => ({
            open: false,
        }),

        computed: {
            alignmentClass: function() {
                return {
                    left: "origin-top-left left-0",
                    right: "origin-top-right right-0",
                }[this.align] || "origin-top"
            },
        },

        created() {
            const closeOnEscape = (e) => {
                if (this.open && e.keyCode === 27) {
                    this.open = false
                }
            }

            this.$once("hook:destroyed", () => {
                document.removeEventListener("keydown", closeOnEscape)
            })

            document.addEventListener("keydown", closeOnEscape)
        },
    }
</script>
