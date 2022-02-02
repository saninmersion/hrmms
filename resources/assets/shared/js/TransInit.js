"use strict"

import { isEmpty as _isEmpty } from "lodash"
import {
    englishToNepaliNumber,
    nepaliToEnglishNumber,
}                              from "nepali-number"
import Vue                     from "vue"

export default () => {
    const translations = window.i18n
    const currentLocale = window.locale

    const trans = (key, replace = {}) => {
        let translated = _.get(translations, key, key)

        if (!_isEmpty(replace)) {
            Object.keys(replace).forEach(key => {
                translated = translated.replace(key, replace[key])
            })
        }

        return translated
    }

    const numberTrans = (number) => {
        return currentLocale === "ne" ? englishToNepaliNumber(number) : nepaliToEnglishNumber(number)
    }

    Vue.prototype.$trans = trans
    Vue.prototype.$currentLocale = currentLocale

    Vue.mixin({
        data: () => ({
            currentLocale: currentLocale,
        }),

        methods: { trans, numberTrans },
    })
}
