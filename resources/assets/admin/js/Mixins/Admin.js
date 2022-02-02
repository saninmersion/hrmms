"use strict"

import { get as _get }   from "lodash"

export default {
    methods: {
        isAuthorized: (page, can) => {
            return _get(page, `props.can.${can}`, false)
        },
    },
}
