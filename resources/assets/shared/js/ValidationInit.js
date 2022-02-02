import Vue              from "vue"
import ValidationErrors from "./Mixins/ValidationErrors"

export default () => {
    Vue.prototype.$validationErrors = Vue.observable({
        errors: {},
    })

    Vue.mixin(ValidationErrors)
}
