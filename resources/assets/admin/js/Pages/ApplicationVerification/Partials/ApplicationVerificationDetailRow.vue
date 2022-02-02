<template>
    <div class="grid grid-cols-12 mb-2">
        <p class="col-span-3 text-l text-black data-block-label" v-text="label"/>

        <slot :value="getFromObject(applicant,name)" name="show">
            <p class="col-span-4 px-2 py-1 rounded" v-text="getFromObject(applicant,name)"/>
        </slot>

        <slot v-if="getFromObject(modified,name)" :value="getFromObject(modified,name, '')" name="input">
            <p class="col-span-4 bg-grey-100 px-2 py-1 rounded" v-text="getFromObject(modified,name)"/>
        </slot>
        <slot v-else-if="getFromObject(applicant,name)" name="input">
            <p class="col-span-4 bg-grey-100 px-2 py-1 rounded"/>
        </slot>

        <toggle-input :disabled="true" :value="toggleInputValue(name)" class="col-span-1 ml-4"/>
    </div>
</template>

<script>

    import ToggleInput from "../../../Components/Forms/ToggleInput"

    export default {
        name: "ApplicationVerificationRow",
        components: { ToggleInput },
        props: {
            name: { type: String, required: true },
            label: { type: String, required: true },
            applicant: { type: Object, required: true, default: () => {} },
            modified: { type: [Object, Array], required: true, default: () => {} },
            checkList: { type: Object, required: true, default: () => {} },
        },

        methods: {
            isWrong(name) {
                return !this.getFromObject(this.checkList, name, false)
            },
            toggleInputValue(name) {
                if (this.checkList[name] === undefined) {
                    return this.modified[name] === undefined;
                }
                return this.checkList[name]
            },
        },
    }
</script>
