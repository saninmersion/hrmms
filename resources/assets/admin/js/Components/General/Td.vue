<template>
    <td v-if="href" style="min-width: 260px;" class="px-4 py-3 border-b text-center">
        <inertia-link
            class="p-4 w-1/4 !text-center"
            :class="'text-primary-500'"
            :href="href">
            <span class="border-r-2 px-1"
                  v-text="total"/>
            <span class="border-r-2 px-1"
                  v-text="totalHouse"/>
            <span class="px-1"
                  v-text="totalFamilies"/>
        </inertia-link>
    </td>
    <td v-else
        style="min-width: 260px;"
        class="px-4 py-3 border-b text-center">
        <span class="border-r-2 px-1"
              v-text="total"/>
        <span class="border-r-2 px-1"
              v-text="totalHouse"/>
        <span class="px-1"
              v-text="totalFamilies"/>
    </td>
</template>

<script>
    export default {
        name: "Table",
        props: {
            total: { type: [Number, String], required: false, default: 0 },
            totalHouse: { type: [Number, String], required: false, default: 0 },
            totalFamilies: { type: [Number, String], required: false, default: 0 },
            href: { type: String, required: false, default: "" },
        },
    }
</script>
