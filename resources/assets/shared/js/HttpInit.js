"use strict"

import * as axios from "axios"
import Vue        from "vue"

export default () => {
    axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"

    const token = document.head.querySelector("meta[name='csrf-token']")

    if (token) {
        axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content
    } else {
        console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token")
    }

    Vue.prototype.$http = axios.create()
}
