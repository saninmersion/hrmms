<template>
    <div class="radio-btn-group radio-btn-group--blue">
        <radio-input
            :checked="gender === 'male'"
            :disabled="disabled && gender !== 'male'"
            :name="name"
            :target-id="`${name}-male`"
            :label="trans('application.gender-male')"
            @checked="handleOnChecked('male')"/>

        <radio-input
            :checked="gender === 'female'"
            :disabled="disabled && gender !== 'female'"
            :name="name"
            :target-id="`${name}-female`"
            :label="trans('application.gender-female')"
            @checked="handleOnChecked('female')"/>

        <radio-input
            :checked="gender === 'other'"
            :disabled="disabled && gender !== 'other'"
            :name="name"
            :target-id="`${name}-other`"
            :label="trans('application.gender-other')"
            @checked="handleOnChecked('other')"/>
    </div>
</template>

<script type="text/ecmascript-6">
    import RadioInput from "./RadioInput"

    export default {
        name: "GenderInput",

        components: { RadioInput },

        props: {
            value: { type: String, required: false, default: null },
            name: { type: String, required: true },
            disabled: { type: Boolean, default: false },
        },

        data: () => ({
            gender: null,
        }),

        watch: {
            value: {
                handler(gender) {
                    this.gender = gender
                },
                immediate: true,
            },
        },

        methods: {
            handleOnChecked(val) {
                this.$emit("input", val)
            },
        },
    }
</script>
