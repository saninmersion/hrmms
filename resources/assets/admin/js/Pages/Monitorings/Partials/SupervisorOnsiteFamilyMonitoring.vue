<template>
    <fragment>
        <fieldset class="fieldset-dashed mt-8">
            <div class="btn-wrap flex md:flex-col">
                <div class="btn-add tooltip__wrapper" @click="addFamilyMonitoring">
                    <Icon class="!w-5 !h-5" name="plus-1"/>
                    <div class="tooltip tooltip--dark hidden md:block">
                        अर्को थप्नुहोस्
                    </div>
                </div>
                <div v-if="showRemoveButton" class="btn-remove" @click="removeFamilyMonitoring">
                    <Icon name="close" class="!w-5 !h-5"/>
                </div>
            </div>
            <div class="rounded bg-white text-base-3">
                <div class="form-input-block grid lg:grid-cols-4 gap-6 p-4">
                    <div>
                        <label-component value="परिवार क्र.सं."/>
                        <input-component
                            :value="family_sn"
                            :name="`${name}.family_sn`"
                            class="block w-full"
                            @input="handleOnChange($event, 'family_sn')"/>
                        <input-error :message="form.error(`${name}.family_sn`)"/>
                    </div>
                    <div>
                        <label-component value="परिवारमूलीको नाम"/>
                        <input-component
                            :value="house_head_name"
                            :name="`${name}.house_head_name`"
                            class="block w-full"
                            @input="handleOnChange($event, 'house_head_name')"/>
                        <input-error :message="form.error(`${name}.house_head_name`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.house_head_name_check`"
                                               :value="house_head_name_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.house_head_name_check`"
                                               @input="handleOnChange($event, 'house_head_name_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.house_head_name_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="जम्मा पुरुष संख्या"/>
                        <input-component
                            :value="male_count"
                            :name="`${name}.male_count`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'male_count')"/>
                        <input-error :message="form.error(`${name}.male_count`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.male_count_check`"
                                               :value="male_count_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.male_count_check`"
                                               @input="handleOnChange($event, 'male_count_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.male_count_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="जम्मा महिला संख्या"/>
                        <input-component
                            :value="female_count"
                            :name="`${name}.female_count`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'female_count')"/>
                        <input-error :message="form.error(`${name}.female_count`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.female_count_check`"
                                               :value="female_count_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.female_count_check`"
                                               @input="handleOnChange($event, 'female_count_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.female_count_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="परिवारको यो गा.पा./न.पा भित्र आफैंले चलन गरेको कृषि प्रयोजनको जग्गा छ ?"/>
                        <input-component
                            :value="agriculture_land"
                            :name="`${name}.agriculture_land`"
                            class="block w-full"
                            @input="handleOnChange($event, 'agriculture_land')"/>
                        <input-error :message="form.error(`${name}.agriculture_land`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.agriculture_land_check`"
                                               :value="agriculture_land_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.agriculture_land_check`"
                                               @input="handleOnChange($event, 'agriculture_land_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.agriculture_land_check`)"/>
                    </div>
                    <div class="md:col-span-2">
                        <label-component value="परिवारमा प्राविधक तथा व्यावसायिक शिक्षा/तलिम लिएका संख्या"/>
                        <input-component
                            :value="professional_training"
                            :name="`${name}.professional_training`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'professional_training')"/>
                        <input-error :message="form.error(`${name}.professional_training`)"/>
                    </div>
                    <div class="md:col-span-2  my-auto">
                        <div class="w-full grid gap-4 lg:grid-cols-2 md:gap-6">
                            <label-component class="!mb-0 !text-base-2">
                                सूचीकरणमा भएको विवरण अनुसार
                            </label-component>
                            <radio-group-input :id="`${name}.professional_training_check`"
                                               :value="professional_training_check"
                                               class="md:!grid-cols-2"
                                               :options="accuracyOptions"
                                               :name="`${name}.professional_training_check`"
                                               @input="handleOnChange($event, 'professional_training_check')"/>
                        </div>
                        <input-error :message="form.error(`${name}.professional_training_check`)"/>
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
        name: "SupervisorOnsiteFamilyMonitoring",
        components: { RadioGroupInput, Icon, LabelComponent, InputError, InputComponent },
        props: {
            name: { type: String, required: true },
            index: { type: Number, required: false, default: 0 },
            form: { type: Object, required: true },
            familyMonitoring: { type: Object, required: true },
            monitoringsCount: { type: Number, required: false, default: 0 },
        },
        data: () => ({
            family_sn: "",
            house_head_name: "",
            house_head_name_check: "",
            male_count: "",
            male_count_check: "",
            female_count: "",
            female_count_check: "",
            agriculture_land: "",
            agriculture_land_check: "",
            professional_training: "",
            professional_training_check: "",
        }),
        inject: ["accuracyOptions"],
        watch: {
            familyMonitoring: {
                handler(monitoring) {
                    this.family_sn = monitoring.family_sn || null
                    this.house_head_name = monitoring.house_head_name || null
                    this.house_head_name_check = monitoring.house_head_name_check || null
                    this.male_count = monitoring.male_count || null
                    this.male_count_check = monitoring.male_count_check || null
                    this.female_count = monitoring.female_count || null
                    this.female_count_check = monitoring.female_count_check || null
                    this.agriculture_land = monitoring.agriculture_land || null
                    this.agriculture_land_check = monitoring.agriculture_land_check || null
                    this.professional_training = monitoring.professional_training || null
                    this.professional_training_check = monitoring.professional_training_check || null
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
                    family_sn: this.family_sn,
                    house_head_name: this.house_head_name,
                    house_head_name_check: this.house_head_name_check,
                    male_count: this.male_count,
                    male_count_check: this.male_count_check,
                    female_count: this.female_count,
                    female_count_check: this.female_count_check,
                    agriculture_land: this.agriculture_land,
                    agriculture_land_check: this.agriculture_land_check,
                    professional_training: this.professional_training,
                    professional_training_check: this.professional_training_check,
                }
                this.$emit("input", data)
            },
            addFamilyMonitoring: function() {
                this.$emit("add")
            },
            removeFamilyMonitoring: function() {
                this.$emit("remove")
            },
        },
    }
</script>

<style scoped>

</style>
