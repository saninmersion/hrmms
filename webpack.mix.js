const mix = require("laravel-mix")
const path = require("path")
const tailwind = require("tailwindcss")

const application = process.env.application
const applicationMap = {
    front: "front",
    auth: "admin",
    admin: "admin",
}

mix.setPublicPath(path.normalize(`public/assets/${application}`))
mix.setResourceRoot(`/assets/${application}`)

if (mix.inProduction()) {
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                    drop_debugger: true,
                },
            },
        },
    })
} else {
    mix.webpackConfig({ devtool: "inline-source-map" }).sourceMaps()
}

function resolve(dir) {
    return path.join(__dirname, dir)
}

mix.webpackConfig({
    resolve: {
        alias: {
            "@": resolve("resources/js"),
            // vue$: "vue/dist/vue.esm.js",
            ziggy: path.resolve("vendor/tightenco/ziggy/dist"),
        },
    },

    output: {
        publicPath: path.normalize(`/assets/${application}`),
        chunkFilename: "[name].js?id=[chunkhash]",
    },

    watchOptions: {
        ignored: /node_modules/,
    },
}).babelConfig({
    plugins: ["@babel/plugin-syntax-dynamic-import"],
})

mix.js(`resources/assets/${application}/js/app.js`, "js/app.js").vue({
    version: 2,
    extractStyles: true,
})

mix.sass(`resources/assets/${application}/sass/app.scss`, "css/app.css").version()
mix.postCss("resources/assets/shared/sass/tailwind.css", "css/tailwind.css", [
    require("postcss-import"),
    tailwind({ config: `./tailwind-${applicationMap[application]}.config.js` }),
])

mix.options({
    processCssUrls: true,
})
mix.extract()
mix.version()
