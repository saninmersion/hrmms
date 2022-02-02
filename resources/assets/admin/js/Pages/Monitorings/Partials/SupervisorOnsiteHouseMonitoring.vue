<template>
    <fragment>
        <fieldset class="fieldset-dashed mt-8">
            <div class="btn-wrap flex md:flex-col">
                <div class="btn-add tooltip__wrapper" @click="addHouseMonitoring">
                    <Icon class="!w-5 !h-5" name="plus-1"/>
                    <div class="tooltip tooltip--dark hidden md:block">
                        अर्को थप्नुहोस्
                    </div>
                </div>
                <div v-if="showRemoveButton" class="btn-remove" @click="removeHouseMonitoring">
                    <Icon name="close" class="!w-5 !h-5"/>
                </div>
            </div>
            <div class="rounded bg-white text-base-3">
                <div class="form-input-block grid lg:grid-cols-4 gap-6 p-4">
                    <div>
                        <label-component value="जनगणना घर क्रम संख्या"/>
                        <input-component
                            :value="house_sn"
                            :name="`${name}.house_sn`"
                            class="block w-full"
                            @input="handleOnChange($event, 'house_sn')"/>
                        <input-error :message="form.error(`${name}.house_sn`)"/>
                    </div>
                    <div>
                        <label-component value="घरको तला संख्या"/>
                        <input-component
                            :value="floor_count"
                            :name="`${name}.floor_count`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'floor_count')"/>
                        <input-error :message="form.error(`${name}.floor_count`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.floor_count_check`"
                                               :value="floor_count_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.floor_count_check`"
                                               @input="handleOnChange($event, 'floor_count_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.floor_count_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="घर मुख्यरुपमा के को लागि (कोड)"/>
                        <input-component
                            :value="house_purpose"
                            :name="`${name}.house_purpose`"
                            class="block w-full"
                            @input="handleOnChange($event, 'house_purpose')"/>
                        <input-error :message="form.error(`${name}.house_purpose`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.house_purpose_check`"
                                               :value="house_purpose_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.house_purpose_check`"
                                               @input="handleOnChange($event, 'house_purpose_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.house_purpose_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="घरकाे परिवार संख्या"/>
                        <input-component
                            :value="family_count"
                            :name="`${name}.family_count`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'family_count')"/>
                        <input-error :message="form.error(`${name}.family_count`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.family_count_check`"
                                               :value="family_count_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.family_count_check`"
                                               @input="handleOnChange($event, 'family_count_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.family_count_check`)"/>
                    </div>
                </div>
            </div>
        </fieldset>
    </fragment>
</template>

<script>
    import {
        InputError,
        LabelComponent,
    }                      from "../../../Components/Forms"
    import RadioGroupInput from "../../../Components/Forms/RadioGroupInput"
    import InputComponent  from "../../../Components/Forms/TextInput"
    import Icon            from "../../../Components/General/Icon"

    export default {
        name: "SupervisorOnsiteHouseMonitoring",
        components: { RadioGroupInput, Icon, LabelComponent, InputError, InputComponent },
        mixins: {},
        props: {
            name: { type: String, required: true },
            index: { type: Number, required: false, default: 0 },
            form: { type: Object, required: true },
            houseMonitoring: { type: Object, required: true },
            monitoringsCount: { type: Number, required: false, default: 0 },
        },
        data: () => ({
            house_sn: "",
            floor_count: "",
            floor_count_check: "",
            house_purpose: "",
            house_purpose_check: "",
            family_count: "",
            family_count_check: "",
        }),
        inject: ["accuracyOptions"],
        watch: {
            houseMonitoring: {
                handler(monitoring) {
                    this.house_sn = monitoring.house_sn || null
                    this.floor_count = monitoring.floor_count || null
                    this.floor_count_check = monitoring.floor_count_check || null
                    this.house_purpose = monitoring.house_purpose || null
                    this.house_purpose_check = monitoring.house_purpose_check || null
                    this.family_count = monitoring.family_count || null
                    this.family_count_check = monitoring.family_count_check || null
                },
                immediate: true,
            },
        },
        computed: {
            showRemoveButton: function() {
                return !(this.monitoringsCount === 1 && this.index === 0)
            },
        },
        methods: {
            handleOnChange(value, name) {
                this.$set(this, name, value)

                this.emitData()
            },
            emitData() {
                const data = {
                    house_sn: this.house_sn,
                    floor_count: this.floor_count,
                    floor_count_check: this.floor_count_check,
                    house_purpose: this.house_purpose,
                    house_purpose_check: this.house_purpose_check,
                    family_count: this.family_count,
                    family_count_check: this.family_count_check,
                }
                this.$emit("input", data)
            },
            addHouseMonitoring: function() {
                this.$emit("add")
            },
            removeHouseMonitoring: function() {
                this.$emit("remove")
            },
        },
    }
</script>

<style scoped>

</style>
