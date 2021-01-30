<template>
    <span class="tab-title inline-flex items-center">
        <span v-if="tab.tabInfo.beforeIcon" v-html="tab.tabInfo.beforeIcon" class="mr-1" />
        <span v-if="tab.tabInfo.titleAsHtml" v-html="tab.tabInfo.title" />
        <span v-else>{{ tab.tabInfo.title }}</span>
        <span v-if="tab.tabInfo.afterIcon" v-html="tab.tabInfo.afterIcon" class="ml-1" />
    </span>
</template>

<script>
export default {
    name: 'TabTitle',
    props: {
        tab: {
            type: Object,
            required: true,
        },
    },
};
</script>
