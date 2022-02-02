<template>
    <div class="flex flex-col justify-center px-4 py-4 bg-red-600 border border-gray-300 rounded">
        <div>
            <p class="text-3xl font-semibold text-center" v-text="value"/>
            <p class="text-md mt-3 text-center" v-text="text"/>
        </div>
    </div>
</template>

<script>
    export default {
        name: "StatCard",
        props: {
            text: { type: String, required: true },
            value: { type: [String, Number], required: true },
        },
    }
</script>

<style scoped>

</style>
