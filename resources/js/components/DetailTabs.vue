<template>
  <div>
    <slot>
      <Heading :level="1" v-text="panel.name" />

      <p
          v-if="panel.helpText"
          :class="panel.helpText ? 'mt-2' : 'mt-3'"
          class="text-gray-500 text-sm font-semibold italic"
          v-html="panel.helpText"
      ></p>
    </slot>

    <div id="tabs">
      <div class="sm:hidden">
        <label class="sr-only" for="tabs">Select a tab</label>

        <select
            id="tab"
            v-model="selectedTab"
            class="form-select block px-3 w-full focus:ring-sky-200 focus:border-sky-200 border-gray-300 rounded-md capitalize"
            @change="handleTabClick(selectedTab)"
        >
          <option
              v-for="(tab, key) in getSortedTabs(tabs)"
              :key="key"
              :selected="getIsTabCurrent(tab)"
              :value="tab"
              class="p-2"
          >
            {{ tab.name }}
          </option>
        </select>
      </div>
      <div class="hidden sm:block">
        <nav
            aria-label="Tabs"
            class="relative z-0 flex divide-x divide-gray-200 bg-white dark:bg-gray-800 rounded-lg shadow mx-auto"
        >
          <Button
              v-for="(tab, key) in getSortedTabs(tabs)"
              :key="key"
              :dusk="tab.slug + '-tab'"
              :class="getIsTabCurrent(tab) ? 'text-primary-500' : 'text-gray-800'"
              class="first:rounded-l-lg max-w-[350px] last:rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 font-semibold text-center hover:bg-gray-50 focus:z-10b cursor-pointer"
              @click.prevent="handleTabClick(tab)"
          >
            <span class="capitalize">{{ tab.properties.title }}</span>
            <span
                v-if="getIsTabCurrent(tab)"
                aria-hidden="true"
                class="bg-primary-500 absolute inset-x-0 bottom-0 h-0.5"
            ></span>
            <span
                v-else
                aria-hidden="true"
                class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"
            ></span>
          </Button>
        </nav>
      </div>
    </div>

    <Card
        v-for="(tab, index) in tabs"
        :key="'related-tabs-fields' + index"
        :ref="getTabRefName(tab)"
        :class="[
                    tab.slug,
                ]"
        :label="tab.name"
        v-show="getIsTabCurrent(tab)"
        class="mt-8 py-2 px-6"
    >
      <div :class="getBodyClass(tab)">
        <KeepAlive v-for="(field, index) in tab.fields" :key="index">
          <component
              :is="getComponentName(field)"
              :class="{'remove-bottom-border': index === tab.fields.length - 1}"
              :field="field"
              :index="index"
              :resource="resource"
              :resource-id="resourceId"
              :resource-name="resourceName"
              @actionExecuted="actionExecuted"
          />
        </KeepAlive>
      </div>
    </Card>
  </div>
</template>

<script>
import BehavesAsPanel from '../../../vendor/laravel/nova/resources/js/mixins/BehavesAsPanel';
import Heading from '../../../vendor/laravel/nova/resources/js/components/Heading.vue';
import Card from '../../../vendor/laravel/nova/resources/js/components/Card.vue';
import _ from "lodash";

export default {
  mixins: [BehavesAsPanel],

  components: {Card, Heading},

  props: {
    panel: {
      type: Object,
      required: true,
    },

    name: {
      default: 'Panel',
    },

    mode: {
      type: String,
      default: 'detail',
    },

    fields: {
      type: Array,
      default: [],
    },

    resourceName: {
      type: String,
      required: true,
    },

    resourceId: {
      type: [Number, String],
    },

    relatedResourceName: {
      type: String,
    },

    relatedResourceId: {
      type: [Number, String],
    },

    viaResource: {
      type: String,
    },

    viaResourceId: {
      type: [Number, String],
    },

    viaRelationship: {
      type: String,
    },
  },

  data() {
    return {
      tabs: null,
      selectedTab: {}
    };
  },

  /**
   * Get the tabs and their respective fields when mounted
   * and show the first tab by default.
   */
  mounted() {
    const tabs = this.tabs = this.panel.fields.reduce((tabs, field) => {
      if (!(field.tabSlug in tabs)) {
        tabs[field.tabSlug] = {
          name: field.tab,
          slug: field.tabSlug,
          position: field.tabPosition,
          init: false,
          listable: field.listableTab,
          fields: [],
          properties: field.tabInfo,
        };
      }

      tabs[field.tabSlug].fields.push(field);

      return tabs;
    }, {});

    this.handleTabClick(tabs[Object.keys(tabs)[0]], true);
  },

  methods: {

    /**
     * Handle tabs being clicked
     *
     * @param tab
     * @param updateUri
     */
    handleTabClick(tab, updateUri = true) {
      this.selectedTab = tab;
    },

    /**
     * Get the component name.
     *
     * @param field
     * @returns {string}
     */
    getComponentName(field) {
      return field.prefixComponent
          ? 'detail-' + field.component
          : field.component
    },

    /**
     * Get body class for tabbed field panel
     *
     * @param tab
     * @returns {string}
     */
    getBodyClass(tab) {
      return tab.properties.bodyClass;
    },

    /**
     * Get reference name for tab
     *
     * @param tab
     * @returns {string}
     */
    getTabRefName(tab) {
      return `tab-${tab.slug}`;
    },

    /**
     * Check if the specified tab is the current opened one
     *
     * @param tab
     * @returns {boolean}
     */
    getIsTabCurrent(tab) {
      return this.selectedTab === tab || (!this.selectedTab && this.tabs[Object.keys(this.tabs)[0]] === tab)
    },

    /**
     * Sort the tabs object by their respective positions using lodash
     *
     * @param tabs
     * @returns {object}
     */
    getSortedTabs(tabs) {
      return _.orderBy(tabs, [c => c.position], ['asc']);
    }

  },
};
</script>
