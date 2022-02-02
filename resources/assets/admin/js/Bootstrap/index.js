"use strict"

import { plugin as InertiaPlugin } from "@inertiajs/inertia-vue"
import { InertiaProgress }         from "@inertiajs/progress"
import { InertiaForm }             from "laravel-jetstream"
import PortalVue                   from "portal-vue"
import Vue                         from "vue"
import { Plugin as Fragment }      from "vue-fragment"
import FontAwesomeInit             from "../../../shared/js/FontAwesomeInit"
import HttpInit                    from "../../../shared/js/HttpInit"
import CommonMixins                from "../../../shared/js/Mixins/Common"
import RouteInit                   from "../../../shared/js/RouteInit"
import TransInit                   from "../../../shared/js/TransInit"
import ValidationInit              from "../../../shared/js/ValidationInit"
import Admin                       from "../Mixins/Admin"

export const Bootstrap = () => {
    Vue.use(InertiaPlugin)
    Vue.use(InertiaForm)
    Vue.use(PortalVue)
    Vue.use(Fragment)

    InertiaProgress.init({
        // The delay after which the progress bar will
        // appear during navigation, in milliseconds.
        delay: 250,

        // The color of the progress bar.
        color: "#29d",

        // Whether to include the default NProgress styles.
        includeCSS: true,

        // Whether the NProgress spinner will be shown.
        showSpinner: true,
    })

    HttpInit()
    RouteInit()
    FontAwesomeInit()
    TransInit()
    ValidationInit()

    window.Bus = new Vue({ name: "Bus" })

    Vue.mixin(CommonMixins)
    Vue.mixin(Admin)

    Vue.config.errorHandler = (err, vm, info) => {
        console.error(err, vm, info)
    }
}
