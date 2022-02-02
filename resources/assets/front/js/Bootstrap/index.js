"use strict"

import Vue                    from "vue"
import { Plugin as Fragment } from "vue-fragment"
import HttpInit               from "../../../shared/js/HttpInit"
import CommonMixins           from "../../../shared/js/Mixins/Common"
import TransInit              from "../../../shared/js/TransInit"
import ValidationInit         from "../../../shared/js/ValidationInit"

export const Bootstrap = () => {
    HttpInit()
    TransInit()
    ValidationInit()

    Vue.use(Fragment)

    window.Bus = new Vue({ name: "Bus" })

    Vue.mixin(CommonMixins)

    Vue.config.errorHandler = (err, vm, info) => {
        console.error(err, vm, info)
    }
}
