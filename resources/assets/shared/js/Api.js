"use strict"
import Http    from "axios"
import { get } from "lodash"
import qs      from "qs"

const cache = []

class Api {
    async get(resource, params = {}, options = {}) {
        const { customResponse, caching, showLoader } = {
            customResponse: true,
            caching: false,
            showLoader: true,
            ...options,
        }

        if (showLoader) { this.startLoading() }

        const config = {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded;charset=utf-8",
            },
            params: params,
        }

        try {
            let res = {}
            const key = encodeURIComponent(resource + JSON.stringify(params))

            if (caching && cache[key]) {
                res = cache[key]
            } else {
                res = await Http.get(resource, config)
                cache[key] = res
            }

            if (!customResponse) {
                return res
            }

            return this.successResponse(res)
        } catch (error) {
            return this.errorResponse(error)
        }
    }

    async post(resource, params = {}, options = {}) {
        const { customResponse, processParams, contentType, showLoader } = {
            customResponse: true,
            processParams: true,
            contentType: null,
            showLoader: true,
            ...options,
        }

        if (showLoader) { this.startLoading() }

        const config = {
            headers: {
                "Content-Type": contentType || "application/x-www-form-urlencoded;charset=utf-8",
            },
        }

        try {
            const response = await Http.post(
                resource,
                processParams ? qs.stringify(params) : params,
                config,
            )

            if (!customResponse) {
                return response
            }

            return this.successResponse(response)
        } catch (error) {
            return this.errorResponse(error)
        }
    }

    async delete(resource, options = {}) {
        const { customResponse, showLoader } = {
            customResponse: true,
            showLoader: true,
            ...options,
        }

        if (showLoader) { this.startLoading() }

        try {
            const response = await Http.delete(resource)

            if (!customResponse) {
                return response
            }

            return this.successResponse(response)
        } catch (error) {
            return this.errorResponse(error)
        }
    }

    successResponse(response) {
        this.stopLoading()
        console.log(response.request, response.request._redirectable, "Response From Inside")

        return this.response(response)
    }

    errorResponse(error) {
        this.stopLoading()

        const errorMessage = error.response.status === 404
            ? "Page not found."
            : get(error, "response.data.message", "An error occurred. Please contact administrator.")
        window.Bus.$emit("notification", {
            type: "error",
            message: errorMessage,
        })

        throw error
    }

    response({ data, status, headers }) {
        return {
            body: data,
            status,
            headers,
        }
    }

    startLoading() {
        window.Bus.$emit("start-loader")
    }

    stopLoading() {
        window.Bus.$emit("stop-loader")
    }

    invalidateCache(resource, params = {}) {
        const key = encodeURIComponent(resource + JSON.stringify(params))

        delete cache[key]
    }
}

export default new Api()
