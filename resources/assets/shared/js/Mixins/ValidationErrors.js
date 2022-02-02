"use strict"

import {
    get as _get,
    has as _has,
    isEmpty as _isEmpty,
} from "lodash"

export default {
    methods: {
        validation() {
            return {
                set: (errors) => {
                    this.$set(this.$validationErrors, "errors", errors)
                },

                hasError: () => {
                    return this.$validationErrors.errors && !_isEmpty(this.$validationErrors.errors)
                },

                has: (key) => _has(this.$validationErrors.errors, key),

                get: (key) => {
                    if (!this.validation().has(key)) {
                        return null
                    }

                    return _get(this.$validationErrors.errors, key)[0]
                },

                clear: (key) => {
                    if (!this.validation().has(key)) {
                        return
                    }

                    this.$delete(this.$validationErrors.errors, key)
                },

                clearAll: () => {
                    this.$validationErrors.errors = {}
                },
            }
        },
    },
}
