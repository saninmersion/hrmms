"use strict"

import Vue           from "vue"
import { Bootstrap } from "./Bootstrap"
import scripts       from "./Bootstrap/scripts"
import Components    from "./Components"

Bootstrap()

// noinspection ObjectAllocationIgnored
new Vue({ /* eslint-disable-line no-new */
    el: "#app",

    components: Components,

    mounted() {
        scripts()
    },
})
