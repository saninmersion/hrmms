<template>
    <div class="lang-switcher flex  justify-center dropdown-menu">
        <button class="dropdown-btn" v-text="selectedLocale === 'ne' ? language.ne : language.en"/>
        <ul class="dropdown text-black shadow">
            <li @click="handleLocalChange('ne')">
                {{ language.ne }}
            </li>
            <li @click="handleLocalChange('en')">
                {{ language.en }}
            </li>
        </ul>
    </div>
</template>

<script type="text/ecmascript-6">
    import Api from "../../../shared/js/Api"

    export default {
        name: "LanguageSwitcher",

        props: {
            setLocaleRoute: { type: String, required: true },
        },

        data: () => ({
            selectedLocale: "ne",
            language: {
                ne: "नेपाली",
                en: "English",
            },
        }),

        mounted() {
            this.selectedLocale = this.$currentLocale
        },

        methods: {
            handleDropDown() {

            },
            async handleLocalChange(currentLocale) {
                if (currentLocale !== this.selectedLocale) {
                    this.selectedLocale = currentLocale

                    try {
                        await Api.post(this.setLocaleRoute, { locale: this.selectedLocale })

                        window.location.reload()
                    } catch (e) {
                        console.error(e)
                    }
                }
            },
        },
    }
</script>
