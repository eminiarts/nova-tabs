<template>
  <div :class="darkModeClass">
    <div class="tab-group">
      <slot>
        <Heading v-if="panel.showTitle" :level="1" v-text="panel.name"/>

        <p
          v-if="panel.helpText"
          :class="panel.helpText ? 'tabs-mt-2' : 'tabs-mt-3'"
          class="tabs-text-gray-500 tabs-text-sm tabs-fotabs-semibold tabs-italic"
          v-html="panel.helpText"
        ></p>
      </slot>

      <div id="tabs">
        <div class="block">
          <nav
            aria-label="Tabs"
            class="tab-menu"
          >
            <Button
              v-for="(tab, key) in getSortedTabs(tabs)"
              :key="key"
              :class="getIsTabCurrent(tab) ? 'active text-primary-500' : 'tabs-text-gray-800 dark:tabs-text-gray-50'"
              :dusk="tab.slug + '-tab'"
              class="tab-item"
              @click.prevent="handleTabClick(tab)"
            >
              <span class="capitalize">{{ tab.properties.title }}</span>
              <span
                v-if="getIsTabCurrent(tab)"
                aria-hidden="true"
                class="bg-primary-500 tabs-absolute tabs-inset-x-0 tabs-bottom-0 tabs-h-0.5"
              ></span>
              <span
                v-else
                aria-hidden="true"
                class="tabs-bg-transparent tabs-absolute tabs-inset-x-0 tabs-bottom-0 tabs-h-0.5"
              ></span>
            </Button>
          </nav>
        </div>
      </div>

      <div
        v-for="(tab, index) in getSortedTabs(tabs)"
        v-show="getIsTabCurrent(tab)"
        :key="'related-tabs-fields' + index"
        :ref="getTabRefName(tab)"
        :class="[
                      'tab fields-tab',
                      getIsTabCurrent(tab) ? 'block' : 'hidden',
                      tab.slug,
                  ]"
        :label="tab.name"
      >
        <div :class="getBodyClass(tab)">
          <KeepAlive>
            <component
              :is="getComponentName(field)"
              v-for="(field, index) in tab.fields"
              :key="'tab-' + index"
              ref="fields"
              :class="{'remove-bottom-border': index === tab.fields.length - 1}"
              :errors="validationErrors"
              :field="field"
              :form-unique-id="formUniqueId"
              :related-resource-id="relatedResourceId"
              :related-resource-name="relatedResourceName"
              :resource-id="resourceId"
              :resource-name="resourceName"
              :show-help-text="field.helpText != null"
              :shown-via-new-relation-modal="shownViaNewRelationModal"
              :via-relationship="viaRelationship"
              :via-resource="viaResource"
              :via-resource-id="viaResourceId"
              @field-changed="$emit('field-changed')"
              @file-deleted="$emit('update-last-retrieved-at-timestamp')"
              @file-upload-started="$emit('file-upload-started')"
              @file-upload-finished="$emit('file-upload-finished')"
            />
          </KeepAlive>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BehavesAsPanel from '../../../vendor/laravel/nova/resources/js/mixins/BehavesAsPanel';
import Heading from '../../../vendor/laravel/nova/resources/js/components/Heading.vue';
import Card from '../../../vendor/laravel/nova/resources/js/components/Card.vue';
import orderBy from 'lodash/orderBy';

export default {
  mixins: [BehavesAsPanel],

  components: {Card, Heading},

  props: {
    shownViaNewRelationModal: {
      type: Boolean,
      default: false,
    },

    panel: {
      type: Object,
      required: true,
    },

    name: {
      default: 'Panel',
    },

    mode: {
      type: String,
      default: 'form',
    },

    fields: {
      type: Array,
      default: [],
    },

    formUniqueId: {
      type: String,
    },

    validationErrors: {
      type: Object,
      required: true,
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

  emits: [
    'field-changed',
    'update-last-retrieved-at-timestamp',
    'file-upload-started',
    'file-upload-finished',
  ],

  data() {
    return {
      tabs: null,
      activeTab: '',
      selectedTab: {},
      darkModeClass: ''
    };
  },

  /**
   * Get the tabs and their respective fields when mounted
   * and show the first tab by default.
   */
  mounted() {
    this.observer = new MutationObserver(mutations => {
      for (const m of mutations) {
        const newValue = m.target.getAttribute(m.attributeName);
        this.$nextTick(() => {
          this.darkModeClass = newValue.includes('dark') ? 'tabs-dark' : ''
        });
      }
    });

    this.observer.observe(document.documentElement, {
      attributes: true,
      attributeOldValue: true,
      attributeFilter: ['class'],
    });

    this.darkModeClass = document.documentElement.classList.contains('dark') ? 'tabs-dark' : '';

    const tabs = this.tabs = this.panel.fields.reduce((tabs, field) => {
      if (!(field.tabSlug in tabs)) {
        tabs[field.tabSlug] = {
          name: field.tab,
          slug: field.tabSlug,
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
        ? 'form-' + field.component
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
      return orderBy(tabs, [c => c.position], ['asc']);
    }

  },
  beforeDestroy() {
    this.observer.disconnect();
  },
};
</script>
