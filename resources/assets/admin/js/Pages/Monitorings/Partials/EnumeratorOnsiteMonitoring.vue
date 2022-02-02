<template>
    <div class="grid gap-3">
        <div class="grid md:grid-cols-3 gap-4 md:gap-6">
            <div>
                <label-component value="जनगणना घर क्र.सं."/>
                <input-component
                    :value="onsite_monitoring.house_sn"
                    :name="`${name}.house_sn`"
                    class="block w-full"
                    @input="handleOnChange($event, 'house_sn')"/>
                <input-error :message="form.error(`${name}.house_sn`)"/>
            </div>
            <div>
                <label-component value="जनगणना परिवार क्र.सं."/>
                <input-component
                    :value="onsite_monitoring.family_sn"
                    :name="`${name}.family_sn`"
                    class="block w-full"
                    @input="handleOnChange($event, 'family_sn')"/>
                <input-error :message="form.error(`${name}.family_sn`)"/>
            </div>
            <div class="md:col-start-1">
                <label-component value="परिवारमूलीको नाम"/>
                <input-component
                    :value="onsite_monitoring.head_of_household_name"
                    :name="`${name}.head_of_household_name`"
                    class="block w-full"
                    @input="handleOnChange($event, 'head_of_household_name')"/>
                <input-error :message="form.error(`${name}.head_of_household_name`)"/>
            </div>
            <div>
                <label-component value="परिवारमूलीको उमेर"/>
                <input-component
                    :value="onsite_monitoring.head_of_household_age"
                    :name="`${name}.head_of_household_age`"
                    class="block w-full"
                    @input="handleOnChange($event, 'head_of_household_age')"/>
                <input-error :message="form.error(`${name}.head_of_household_age`)"/>
            </div>
            <div>
                <div class="grid gap-4">
                    <label-component class="!mb-0">
                        परिवारमूलीको लिङ्ग
                    </label-component>
                    <radio-group-input id="neutrality"
                                       v-model="onsite_monitoring.head_of_household_gender"
                                       :options="genderOptions"
                                       name="head_of_houshold_gender"/>
                </div>
                <input-error :message="form.error(`${name}.head_of_household_gender`)"/>
            </div>
        </div>

        <!-- परिचयात्मक खण्ड -->
        <fieldset class="grid gap-6">
            <div>
                <label-component value="अक्सर वसोबास गर्ने परिवारका संख्या"/>
                <div class="grid md:grid-cols-4 gap-4 md:gap-6">
                    <div>
                        <input-component
                            placeholder="पुरुष"
                            :value="onsite_monitoring.resident_family_male"
                            :name="`${name}.resident_family_male`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'resident_family_male')"/>
                        <input-error :message="form.error(`${name}.resident_family_male`)"/>
                    </div>
                    <div>
                        <input-component
                            placeholder="महिला"
                            :value="onsite_monitoring.resident_family_female"
                            :name="`${name}.resident_family_female`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'resident_family_female')"/>
                        <input-error :message="form.error(`${name}.resident_family_female`)"/>
                    </div>
                    <div>
                        <input-component
                            placeholder="जम्मा"
                            :value="onsite_monitoring.resident_family_total"
                            :name="`${name}.resident_family_total`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'resident_family_total')"/>
                        <input-error :message="form.error(`${name}.resident_family_total`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.resident_family_check`"
                                           :value="onsite_monitoring.resident_family_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.resident_family_check`"
                                           @input="handleOnChange($event, 'resident_family_check')"/>
                        <input-error :message="form.error(`${name}.resident_family_check`)"/>
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <label-component value="अनुपस्थित (विदेशमा रहेका) परिवारका संख्या"/>
                </div>
                <div class="grid md:grid-cols-4 gap-4 md:gap-6">
                    <div>
                        <input-component
                            placeholder="पुरुष"
                            :value="onsite_monitoring.absent_family_male"
                            :name="`${name}.absent_family_male`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'absent_family_male')"/>
                        <input-error :message="form.error(`${name}.absent_family_male`)"/>
                    </div>
                    <div>
                        <input-component
                            placeholder="महिला"
                            :value="onsite_monitoring.absent_family_female"
                            :name="`${name}.absent_family_female`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'absent_family_female')"/>
                        <input-error :message="form.error(`${name}.absent_family_female`)"/>
                    </div>
                    <div>
                        <input-component
                            placeholder="जम्मा"
                            :value="onsite_monitoring.absent_family_total"
                            :name="`${name}.absent_family_total`"
                            type="number"
                            class="block w-full"
                            @input="handleOnChange($event, 'absent_family_total')"/>
                        <input-error :message="form.error(`${name}.absent_family_total`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.absent_family_check`"
                                           :value="onsite_monitoring.absent_family_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.absent_family_check`"
                                           @input="handleOnChange($event, 'absent_family_check')"/>
                        <input-error :message="form.error(`${name}.absent_family_check`)"/>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- पारिवारिक खण्ड-->
        <fieldset class="grid gap-6">
            <div>
                <label-component value="तपाईंको परिवारले प्रयोग गरेको घरको स्वामित्व कस्तो हो ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <drop-down-input
                            :class="{'form-input-error' :form.error(`${name}.current_house_owner`)} "
                            :hide-selected="false"
                            :options="homeOwnerOptions"
                            :placeholder="`विकल्प छान्नुहाेस्`"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="onsite_monitoring.current_house_owner"
                            @input="handleOnChange($event, 'current_house_owner')"/>
                        <input-error :message="form.error(`${name}.current_house_owner`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.current_house_check`"
                                           :value="onsite_monitoring.current_house_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.current_house_check`"
                                           @input="handleOnChange($event, 'current_house_check')"/>
                        <input-error :message="form.error(`${name}.current_house_check`)"/>
                    </div>
                </div>
            </div>
            <div>
                <label-component value="तपाईंको परिवारले प्रयोग गरेको घरको छाना के ले बनेकाे छ ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <drop-down-input
                            :class="{'form-input-error' :form.error(`${name}.current_house_roof`)} "
                            :hide-selected="false"
                            :options="roofMaterialOptions"
                            :placeholder="`विकल्प छान्नुहाेस्`"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="onsite_monitoring.current_house_roof"
                            @input="handleOnChange($event, 'current_house_roof')"/>
                        <input-error :message="form.error(`${name}.current_house_roof`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.current_house_roof_check`"
                                           :value="onsite_monitoring.current_house_roof_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.current_house_roof_check`"
                                           @input="handleOnChange($event, 'current_house_roof_check')"/>
                        <input-error :message="form.error(`${name}.current_house_roof_check`)"/>
                    </div>
                </div>
            </div>
            <div class="grid gap-6">
                <label-component value="तपाईंको परिवारमा निम्न घरायसी सुविधा तथा साधनहरू के के छन् ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <span>
                        टेलिभिजन
                    </span>
                    <div>
                        <drop-down-input
                            :class="{'form-input-error' :form.error(`${name}.facility_television_code`)} "
                            :name="`${name}.facility_television_code`"
                            :hide-selected="false"
                            :options="yesNoOptions"
                            :placeholder="`विकल्प छान्नुहाेस्`"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="onsite_monitoring.facility_television_code"
                            @input="handleOnChange($event, 'facility_television_code')"/>
                        <input-error :message="form.error(`${name}.facility_television_code`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.facility_television_check`"
                                           :value="onsite_monitoring.facility_television_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.facility_television_check`"
                                           @input="handleOnChange($event, 'facility_television_check')"/>
                        <input-error :message="form.error(`${name}.facility_television_check`)"/>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <span>  कम्प्युटर/ल्यापटप    </span>
                    <div>
                        <drop-down-input
                            :class="{'form-input-error' :form.error(`${name}.facility_computer_code`)} "
                            :name="`${name}.facility_computer_code`"
                            :hide-selected="false"
                            :options="yesNoOptions"
                            :placeholder="`विकल्प छान्नुहाेस्`"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="onsite_monitoring.facility_computer_code"
                            @input="handleOnChange($event, 'facility_computer_code')"/>
                        <input-error :message="form.error(`${name}.facility_computer_code`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.facility_computer_check`"
                                           :value="onsite_monitoring.facility_computer_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.facility_computer_check`"
                                           @input="handleOnChange($event, 'facility_computer_check')"/>
                        <input-error :message="form.error(`${name}.facility_computer_check`)"/>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <span>
                        रेफ्रिजरेटर
                    </span>
                    <div>
                        <drop-down-input
                            :class="{'form-input-error' :form.error(`${name}.facility_refrigerator_code`)} "
                            :name="`${name}.facility_refrigerator_code`"
                            :hide-selected="false"
                            :options="yesNoOptions"
                            :placeholder="`विकल्प छान्नुहाेस्`"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="onsite_monitoring.facility_refrigerator_code"
                            @input="handleOnChange($event, 'facility_refrigerator_code')"/>
                        <input-error :message="form.error(`${name}.facility_refrigerator_code`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.facility_refrigerator_check`"
                                           :value="onsite_monitoring.facility_refrigerator_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.facility_refrigerator_check`"
                                           @input="handleOnChange($event, 'facility_refrigerator_check')"/>

                        <input-error :message="form.error(`${name}.facility_refrigerator_check`)"/>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- मृत्यु सम्बन्धी विवरण-->
        <fieldset class="grid gap-6">
            <label-component value="गत १२ महिनामा तपाईको परिवारमा कसैको मृत्यु भएको थियो ?"/>
            <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                <div>
                    <drop-down-input
                        :class="{'form-input-error' :form.error(`${name}.family_death`)} "
                        :hide-selected="false"
                        :options="wereWereNotOptions"
                        :placeholder="`विकल्प छान्नुहाेस्`"
                        :searchable="true"
                        :show-placeholder="true"
                        :value="onsite_monitoring.family_death"
                        @input="handleOnChange($event, 'family_death')"/>
                    <input-error :message="form.error(`${name}.family_death`)"/>
                </div>
                <div>
                    <radio-group-input :id="`${name}.family_death_check`"
                                       :value="onsite_monitoring.family_death_check"
                                       class="md:!grid-cols-2"
                                       :options="accuracyOptions"
                                       :name="`${name}.family_death_check`"
                                       @input="handleOnChange($event, 'family_death_check')"/>
                    <input-error :message="form.error(`${name}.family_death_check`)"/>
                </div>
            </div>
        </fieldset>

        <!--  व्यक्तिगत खण्ड-->
        <fieldset class="grid gap-6">
            <div>
                <label-component value="यस परिवारमा १ बर्ष उमेर नपुगेका बालबालिकाको संख्या कति छ ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <input-component
                            placeholder="संख्या"
                            :value="onsite_monitoring.child_less_than_one_count"
                            :name="`${name}.child_less_than_one_count`"
                            class="block w-full"
                            @input="handleOnChange($event, 'child_less_than_one_count')"/>
                        <input-error :message="form.error(`${name}.child_less_than_one_count`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.child_less_than_one_check`"
                                           :value="onsite_monitoring.child_less_than_one_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.child_less_than_one_check`"
                                           @input="handleOnChange($event, 'child_less_than_one_check')"/>
                        <input-error :message="form.error(`${name}.child_less_than_one_check`)"/>
                    </div>
                </div>
            </div>

            <div>
                <label-component value="यस परिवारमा ६० बर्ष वा सो भन्दा बढी उमेर माथिका व्यक्तिको संख्या कति छ ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <input-component
                            placeholder="संख्या"
                            :value="onsite_monitoring.elderly_more_than_60_count"
                            :name="`${name}.elderly_more_than_60_count`"
                            class="block w-full"
                            @input="handleOnChange($event, 'elderly_more_than_60_count')"/>
                        <input-error :message="form.error(`${name}.elderly_more_than_60_count`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.elderly_more_than_60_check`"
                                           :value="onsite_monitoring.elderly_more_than_60_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.elderly_more_than_60_check`"
                                           @input="handleOnChange($event, 'elderly_more_than_60_check')"/>
                        <input-error :message="form.error(`${name}.elderly_more_than_60_check`)"/>
                    </div>
                </div>
            </div>

            <div>
                <label-component value="यस परिवारमा पढ्न र लेख्न दुवै जान्नेको संख्या कति छ ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <input-component
                            placeholder="संख्या"
                            :value="onsite_monitoring.literate_read_write_count"
                            :name="`${name}.literate_read_write_count`"
                            class="block w-full"
                            @input="handleOnChange($event, 'literate_read_write_count')"/>
                        <input-error :message="form.error(`${name}.literate_read_write_count`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.literate_read_write_check`"
                                           :value="onsite_monitoring.literate_read_write_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.literate_read_write_check`"
                                           @input="handleOnChange($event, 'literate_read_write_check')"/>
                        <input-error :message="form.error(`${name}.literate_read_write_check`)"/>
                    </div>
                </div>
            </div>

            <div>
                <label-component value="यस परिवारमा अपाङ्गता भएका व्यक्तिको संख्या कति छ?"/>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6 lg:w-3/4">
                    <div>
                        <input-component
                            placeholder="संख्या"
                            :value="onsite_monitoring.disabled_count"
                            :name="`${name}.disabled_count`"
                            class="block w-full"
                            @input="handleOnChange($event, 'disabled_count')"/>
                        <input-error :message="form.error(`${name}.disabled_count`)"/>
                    </div>
                    <div>
                        <radio-group-input :id="`${name}.disabled_check`"
                                           :value="onsite_monitoring.disabled_check"
                                           class="md:!grid-cols-2"
                                           :options="accuracyOptions"
                                           :name="`${name}.disabled_check`"
                                           @input="handleOnChange($event, 'disabled_check')"/>
                        <input-error :message="form.error(`${name}.disabled_check`)"/>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
    import DropDownInput from "../../../Components/Forms/DropDownInput"
    import InputError from "../../../Components/Forms/InputError"
    import LabelComponent from "../../../Components/Forms/Label"
    import RadioGroupInput from "../../../Components/Forms/RadioGroupInput"
    import InputComponent from "../../../Components/Forms/TextInput"

    export default {
        name: "EnumeratorOnsiteMonitoring",
        components: { DropDownInput, RadioGroupInput, InputError, InputComponent, LabelComponent },
        props: {
            name: { type: String, required: true },
            form: { type: Object, required: true },
            onsiteMonitoring: { type: Object, required: true },
        },
        inject: ["genderOptions", "accuracyOptions", "homeOwnerOptions", "roofMaterialOptions", "wereWereNotOptions", "yesNoOptions"],
        data: () => ({
            onsite_monitoring: {
                house_sn: "",
                family_sn: "",
                head_of_household_name: "",
                head_of_household_age: "",
                head_of_household_gender: "",
                resident_family_total: 0,
                resident_family_male: 0,
                resident_family_female: 0,
                resident_family_check: "",
                absent_family_total: 0,
                absent_family_male: 0,
                absent_family_female: 0,
                absent_family_check: "",
                current_house_owner: "",
                current_house_check: "",
                current_house_roof: "",
                current_house_roof_check: "",
                facility_television_code: "",
                facility_television_check: "",
                facility_computer_code: "",
                facility_computer_check: "",
                facility_refrigerator_code: "",
                facility_refrigerator_check: "",
                family_death: "",
                family_death_check: "",
                child_less_than_one_count: "",
                child_less_than_one_check: "",
                elderly_more_than_60_count: "",
                elderly_more_than_60_check: "",
                literate_read_write_count: "",
                literate_read_write_check: "",
                disabled_count: "",
                disabled_check: "",
            },
        }),
        watch: {
            onsiteMonitoring: {
                handler(monitoring) {
                    this.$set(this, "onsite_monitoring", { ...this.onsite_monitoring, ...monitoring })
                },
                immediate: true,
            },
            "onsite_monitoring.resident_family_male": {
                handler: "handleResidentFamilyTotalChange",
                immediate: true,
            },
            "onsite_monitoring.resident_family_female": {
                handler: "handleResidentFamilyTotalChange",
                immediate: true,
            },
            "onsite_monitoring.absent_family_male": {
                handler: "handleAbsentFamilyTotalChange",
                immediate: true,
            },
            "onsite_monitoring.absent_family_female": {
                handler: "handleAbsentFamilyTotalChange",
                immediate: true,
            },
        },
        methods: {
            handleAbsentFamilyTotalChange() {
                if (!this.onsite_monitoring.absent_family_male || !this.onsite_monitoring.absent_family_female) {
                    console.log("Here")
                    return
                }

                const total = Number.parseInt(this.onsite_monitoring.absent_family_male) + Number.parseInt(this.onsite_monitoring.absent_family_female)
                this.$set(this.onsite_monitoring, "absent_family_total", total)
            },
            handleResidentFamilyTotalChange() {
                if (!this.onsite_monitoring.resident_family_male || !this.onsite_monitoring.resident_family_female) {
                    console.log("Here")
                    return
                }

                const total = Number.parseInt(this.onsite_monitoring.resident_family_male) + Number.parseInt(this.onsite_monitoring.resident_family_female)
                this.$set(this.onsite_monitoring, "resident_family_total", total)
            },
            handleOnChange(value, name) {
                this.$set(this.onsite_monitoring, name, value)
                this.emitData()
            },
            emitData() {
                const data = {
                    house_sn: this.onsite_monitoring.house_sn,
                    family_sn: this.onsite_monitoring.family_sn,
                    head_of_household_name: this.onsite_monitoring.head_of_household_name,
                    head_of_household_age: this.onsite_monitoring.head_of_household_age,
                    head_of_household_gender: this.onsite_monitoring.head_of_household_gender,
                    resident_family_total: this.onsite_monitoring.resident_family_total,
                    resident_family_male: this.onsite_monitoring.resident_family_male,
                    resident_family_female: this.onsite_monitoring.resident_family_female,
                    resident_family_check: this.onsite_monitoring.resident_family_check,
                    absent_family_total: this.onsite_monitoring.absent_family_total,
                    absent_family_male: this.onsite_monitoring.absent_family_male,
                    absent_family_female: this.onsite_monitoring.absent_family_female,
                    absent_family_check: this.onsite_monitoring.absent_family_check,
                    current_house_owner: this.onsite_monitoring.current_house_owner,
                    current_house_check: this.onsite_monitoring.current_house_check,
                    current_house_roof: this.onsite_monitoring.current_house_roof,
                    current_house_roof_check: this.onsite_monitoring.current_house_roof_check,
                    facility_television_code: this.onsite_monitoring.facility_television_code,
                    facility_television_check: this.onsite_monitoring.facility_television_check,
                    facility_computer_code: this.onsite_monitoring.facility_computer_code,
                    facility_computer_check: this.onsite_monitoring.facility_computer_check,
                    facility_refrigerator_code: this.onsite_monitoring.facility_refrigerator_code,
                    facility_refrigerator_check: this.onsite_monitoring.facility_refrigerator_check,
                    family_death: this.onsite_monitoring.family_death,
                    family_death_code: this.onsite_monitoring.family_death_code,
                    family_death_check: this.onsite_monitoring.family_death_check,
                    child_less_than_one_count: this.onsite_monitoring.child_less_than_one_count,
                    child_less_than_one_code: this.onsite_monitoring.child_less_than_one_code,
                    child_less_than_one_check: this.onsite_monitoring.child_less_than_one_check,
                    elderly_more_than_60_count: this.onsite_monitoring.elderly_more_than_60_count,
                    elderly_more_than_60_code: this.onsite_monitoring.elderly_more_than_60_code,
                    elderly_more_than_60_check: this.onsite_monitoring.elderly_more_than_60_check,
                    literate_read_write_count: this.onsite_monitoring.literate_read_write_count,
                    literate_read_write_code: this.onsite_monitoring.literate_read_write_code,
                    literate_read_write_check: this.onsite_monitoring.literate_read_write_check,
                    disabled_count: this.onsite_monitoring.disabled_count,
                    disabled_code: this.onsite_monitoring.disabled_code,
                    disabled_check: this.onsite_monitoring.disabled_check,
                }
                this.$emit("input", data)
            },
        },
    }
</script>

<style scoped>

</style>
