"use strict"

import {
    dom,
    library,
}                          from "@fortawesome/fontawesome-svg-core"
import { fas }             from "@fortawesome/free-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import Vue                 from "vue"

export default (icons) => {
    library.add(icons || fas)

    Vue.component("Fa", FontAwesomeIcon)

    dom.watch()
}
