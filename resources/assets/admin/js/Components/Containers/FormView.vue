<template>
    <div class="sm:px-20 flex justify-center">
        <form
            :class="classes"
            class="py-6 pb-8"
            @submit.prevent="$emit('submitted')">
            <slot/>

            <div v-if="!$slots.actions">
                <primary-button/>
            </div>

            <div v-else class="flex items-center px-4 pt-6">
                <slot name="actions"/>
            </div>
        </form>
    </div>
</template>

<script type="text/ecmascript-6">
    import PrimaryButton from "../../Components/Buttons/PrimaryButton"

    export default {
        name: "FormView",

        components: { PrimaryButton },

        props: {
            classes: { type: String, required: false, default: "w-full" },
        },
    }
</script>
