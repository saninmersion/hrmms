<template lang="html">
    <div class="tabs">
        <ul class="tabs__header">
            <li v-for="(tab) in tabs"
                :key="'tab__' + tab.name"
                :class="{'tab__selected': (tab.name === selectedTab)}"
                @click="setSelectedTab(tab.name)">
                {{ tab.title }}
            </li>
        </ul>
        <div class="tabs__body">
            <slot/>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tabs",
        props: {
            selectedTab: { required: false, type: String, default: "" },
        },
        data() {
            return {
                selectedIndex: 0,
                tabs: [],
            }
        },
        created() {
            this.tabs = this.$children
        },
        mounted() {
            this.selectTab(0)
        },
        methods: {
            selectTab(i) {
                this.selectedIndex = parseInt(i)

                this.tabs.forEach((tab, index) => {
                    tab.isActive = (index === i)
                })
            },
            setSelectedTab: function(value) {
                this.$emit("clicked", value)
            },
        },
    }
</script>
