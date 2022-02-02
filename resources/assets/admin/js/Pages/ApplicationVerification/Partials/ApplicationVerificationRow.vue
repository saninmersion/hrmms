<template>
    <div class="my-4">
        <div class="flex">
            <div class="w-3/5 flex items-center">
                <p class="data-block-label w-2/5" v-text="label"/>
                <div class="w-3/5">
                    <slot :value="getFromObject(applicant,name)" name="show">
                        <p class=" px-2 py-1 rounded" v-text="getFromObject(applicant,name)"/>
                    </slot>
                </div>
            </div>
            <div class="w-1/3 flex items-center justify-between gap-4">
                <slot v-if="isWrong(name)" :handler="handleInput" :value="getFromObject(modified,name, '')" name="input">
                    <input ref="inputRef"
                           :value="getFromObject(modified,name, '')"
                           class="!w-2/3 h-9 border px-2 rounded"
                           type="text"
                           @input="handleInput">
                </slot>
                <span v-else class="w-2/3 h-9 border rounded bg-grey-2"/>
                <toggle-input :value="checklist[name]" @input="handleToggleInput"/>
            </div>
        </div>
    </div>
</template>

<script>
    import ToggleInput from "../../../Components/Forms/ToggleInput"

    export default {
        name: "ApplicationVerificationRow",

        components: {
            ToggleInput,
        },

        props: {
            name: { type: String, required: true },
            label: { type: String, required: true },
            applicant: { type: Object, required: true, default: () => {} },
            modified: { type: Object, required: true, default: () => {} },
            checkList: { type: Object, required: true, default: () => {} },
        },
        data() {
            return {
                checklist: {},
            }
        },

        watch: {
            checkList: {
                handler(v) {
                    this.$set(this, "checklist", { ...v })
                },
                deep: true,
                immediate: true,
            },
        },
        methods: {
            isWrong(name) {
                return !this.getFromObject(this.checkList, name, false)
            },
            handleInput(e) {
                const value = e.currentTarget ? e.currentTarget.value : e
                this.$emit("modified", { name: this.name, value: value })
            },
            handleToggleInput(v) {
                this.$set(this.checklist, this.name, v)
                this.$emit("update_checklist", this.checklist)
            },
        },
    }
</script>

<style scoped>

</style>
