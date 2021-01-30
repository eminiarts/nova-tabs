<template>
    <div>
        <div>
            <div v-for="(field, index) in fieldsOutsideTabs" :key="index">
                <component
                    :is="'form-' + field.component"
                    :errors="errors"
                    :resource-name="resourceName"
                    :resource-id="resourceId"
                    :field="field"
                    :via-resource="viaResource"
                    :via-resource-id="viaResourceId"
                    :via-relationship="viaRelationship"
                />
            </div>
        </div>

        <div class="relationship-tabs-panel card w-full">
            <div class="flex flex-row overflow-x-auto">
                <div
                    v-for="(tab, key) in tabs"
                    class="py-5 px-8 border-b-2 focus:outline-none tab cursor-pointer flex items-center"
                    :class="getTabClass(tab)"
                    :key="key"
                    @click="handleTabClick(tab, $event)"
                >
                    <tab-title :tab="tab" />
                </div>
                <div class="flex-1 border-b-2 border-40"></div>
            </div>
            <div
                v-for="(tab, index) in tabs"
                v-show="tab.slug === activeTab"
                :key="'related-tabs-fields' + index"
                :label="tab.name"
            >
                <div
                    :class="getBodyClass(tab)"
                >
                    <component
                        ref="fields"
                        v-for="(field, index) in tab.fields"
                        :class="{'remove-bottom-border': index === tab.fields.length - 1}"
                        :key="'tab-' + index"
                        :is="'form-' + field.component"
                        :resource-name="resourceName"
                        :resource-id="resourceId"
                        :resource="resource"
                        :errors="errors"
                        :field="field"
                        :via-resource="viaResource"
                        :via-resource-id="viaResourceId"
                        :via-relationship="viaRelationship"
                        @actionExecuted="actionExecuted"
                        :show-help-text="field.helpText !== null"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    FormField,
    HandlesValidationErrors,
    InteractsWithResourceInformation,
} from 'laravel-nova';

import {changeActiveTab} from '../util/tab-updater';
import TabTitle from './TabTitle';

export default {
    components: {
        TabTitle,
    },
    mixins: [
        HandlesValidationErrors,
        FormField,
        InteractsWithResourceInformation,
    ],
    props: [
        'resource',
        'resourceName',
        'resourceId',
        'field',
        'errors',
        'viaResource',
        'viaRelationship',
        'viaResourceId',
    ],
    data() {
        return {
            tabs: null,
            activeTab: '',
            fieldsOutsideTabs: [],
        };
    },
    watch: {
        errors: {
            handler: function() {
                for (const key of Object.keys(this.tabs)) {
                    if (this.tabHasErrors(this.tabs[key])) {
                        this.handleTabClick(this.tabs[key]);
                        break;
                    }
                }
            },
            deep: true,
        },
    },
    mounted() {
        const tabs = this.tabs = _.toArray(this.field.fields).reduce((tabs, field) => {
            if (!field.tab) {
                this.fieldsOutsideTabs.push(field);
                return tabs;
            }

            if (!Object.hasOwnProperty.call(tabs, field.tab)) {
                tabs[field.tab] = {
                    name: field.tab,
                    slug: field.tabSlug,
                    listable: field.listableTab,
                    fields: [],
                    properties: field.tabInfo,
                };
            }
            tabs[field.tab].fields.push(field);

            return tabs;
        }, {});

        this.handleTabClick({
            slug: this.$route.query.tab || tabs[Object.keys(tabs)[0]].slug,
        });
    },
    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            _.each(this.field.fields, field => {
                field.fill(formData);
            });
        },
        /**
         * Handle the actionExecuted event and pass it up the chain.
         */
        actionExecuted() {
            this.$emit('actionExecuted');
        },
        handleTabClick(tab) {
            const cur = this.$router.currentRoute.query;
            this.activeTab = tab.slug;

            if (!cur || cur.tab !== tab.slug) {
                changeActiveTab(this.$router, tab.slug);
            }

            // When code fields are not visible initially they are not loaded
            // See https://stackoverflow.com/questions/8349571/codemirror-editor-is-not-loading-content-until-clicked
            setTimeout(this.refreshCodeFields, 1);
        },
        tabHasErrors(tab) {
            const hasErrors = Object.keys(this.errors.errors).some(key => {
                return _.includes(tab.fields.map(o => o.attribute), key);
            });

            tab.hasErrors = hasErrors;

            return hasErrors;
        },
        getTabClass(tab) {
            const classes = [];

            if (this.activeTab === tab.slug) {
                classes.push('text-grey-black font-bold border-primary');
            } else {
                classes.push('text-grey font-semibold border-40');
            }

            if (this.tabHasErrors(tab)) {
                classes.push('text-error');
            }

            return classes.concat(tab.properties.tabClass);
        },
        getBodyClass(tab) {
            const classes = [];

            if (!tab.listable) {
                classes.push('px-6 py-3');
            }

            return classes.concat(tab.properties.bodyClass);
        },
        refreshCodeFields() {
            this.$refs.fields
                .filter(field => 'codemirror' in field)
                .forEach(field => field.codemirror.refresh());
        },
    },
};
</script>

<style lang="scss">
.relationship-tabs-panel {
    .text-error {
        color: var(--danger);

        &.border-primary {
            border-color: var(--danger);
        }
    }

    .card {
        box-shadow: none;
    }

    h1 {
        display: none;
    }

    .tab {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
    }

    .tab-content > div > .relative > .flex {
        justify-content: flex-end;
        padding-left: 0.75rem;
        padding-right: 0.75rem;

        position: absolute;
        top: 0;
        right: 0;
        transform: translateY(-100%);

        align-items: center;
        height: 62px;

        > .mb-6 {
            margin-bottom: 0;
        }

        > .w-full {
            width: auto;
            margin-left: 1.5rem;
        }
    }
}
</style>
