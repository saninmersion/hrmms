<template>
    <footer class="block py-4">
        <div class="container mx-auto">
            <div class="flex flex-wrap items-center md:justify-between justify-center">
                <div class="text-sm text-gray-600 py-1 text-center md:text-left">
                    Copyright © <span id="get-current-year"/>
                    <a :href="$page.props.app.copyright.url"
                       target="_blank"
                       class="text-gray-600 hover:text-gray-800 text-sm font-semibold py-1"
                       v-text="$page.props.app.copyright.name"/>
                </div>

                <div class="text-sm text-gray-600 py-1 text-center md:text-left">
                    Developed by: <span id="get-current-year"/>
                    <a :href="$page.props.app.developer.url"
                       target="_blank"
                       class="text-gray-600 hover:text-gray-800 text-sm font-semibold py-1"
                       v-text="$page.props.app.developer.name"/>
                </div>
            </div>
        </div>
    </footer>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "AppFooter",
    }
</script>
