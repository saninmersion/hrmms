<template>
    <div>
        <transition name="fade">
            <div v-if="$page.props.flash.success && show" class="flash-message text-success bg-success-50 flex items-center gap-4 rounded-md px-6 py-4 mb-8">
                <Icon class="!w-6 !h-6 !mr-0" name="success"/>
                <p class="font-medium">
                    {{ $page.props.flash.success }}
                </p>
            </div>
            <div v-if="($page.props.flash.error ) && show" class="flash-message text-warning bg-danger-100 flex items-center gap-4 rounded-md px-6 py-4 mb-8">
                <div class="btn-remove !cursor-auto">
                    <Icon name="close"/>
                </div>
                <p v-if="$page.props.flash.error">
                    {{ $page.props.flash.error }}
                </p>
            </div>
        </transition>
    </div>
</template>

<script>

    import Icon from "./Icon"

    export default {
        name: "FlashMessage",
        components: { Icon },
        data() {
            return {
                show: true,
            }
        },
        watch: {
            "$page.props.flash": {
                handler() {
                    this.show = true
                },
                deep: true,
            },
        },
        mounted() {
            setTimeout(() => { this.show = false }, 3000)
        },
    }
</script>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.5s ease-out;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .flash-message .btn-remove:hover {
        opacity: 1;
    }
</style>
