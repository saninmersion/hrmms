"use strict"

import Vue   from "vue"
import route from "ziggy"

export default () => {
    const routes = Ziggy // eslint-disable-line no-undef

    const customRoute = (name, params, absolute) => route(name, params, absolute, routes)

    Vue.prototype.$route = customRoute

    Vue.mixin({
        methods: {
            route: customRoute,
        },
    })
}
