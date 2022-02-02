"use strict"

import { get as _get } from "lodash"
import { isArray }     from "../Helpers"

export default {
    methods: {
        getFromObject($obj, $key, $default = null) {
            return _get($obj, $key, $default)
        },

        isArray(arr) {
            return isArray(arr)
        },

        decimalOnly(event) {
            const code = event.charCode || event.keyCode

            if (code > 31 && (code < 48 || code > 57) && code !== 46) {
                event.preventDefault()
            }

            if (event.target.value.indexOf(".") !== -1 && code === 46) {
                event.preventDefault()
            }

            return true
        },

        numberOnly(event) {
            const code = event.charCode || event.keyCode

            if (code > 31 && (code < 48 || code > 57)) {
                event.preventDefault()
            }

            return true
        },

        prepareFormData(data) {
            const formData = new FormData()

            function appendFormData(data, label) {
                label = label || ""
                if (data instanceof File) {
                    formData.append(label, data)
                } else if (Array.isArray(data)) {
                    for (let i = 0; i < data.length; i++) {
                        appendFormData(data[i], label + "[" + i + "]")
                    }
                } else if (typeof data === "object" && data) {
                    for (const key in data) {
                        // eslint-disable-next-line no-prototype-builtins
                        if (data.hasOwnProperty(key)) {
                            if (label === "") {
                                appendFormData(data[key], key)
                            } else {
                                appendFormData(data[key], label + "[" + key + "]")
                            }
                        }
                    }
                } else {
                    if (data !== null && typeof data !== "undefined") {
                        formData.append(label, data)
                    }
                }
            }

            appendFormData(data)

            return formData
        },
    },
}
