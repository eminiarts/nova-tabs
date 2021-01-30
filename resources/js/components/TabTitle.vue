<template>
    <span class="tab-title inline-flex items-center">
        <span v-if="tab.properties.beforeIcon" v-html="tab.properties.beforeIcon" class="mr-1" />
        <span v-if="tab.properties.titleAsHtml" v-html="tab.properties.title" />
        <span v-else>{{ tab.properties.title }}</span>
        <span v-if="tab.properties.afterIcon" v-html="tab.properties.afterIcon" class="ml-1" />
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
