<template>
  <div>
    <div>
      <div v-for="(field, index) in fieldsOutsideTabs" :key="index">
        <component
          :is="'form-' + field.component"
          :errors="errors"
          :resource-name="resourceName"
          :field="field"
          :via-resource="viaResource"
          :via-resource-id="viaResourceId"
          :via-relationship="viaRelationship"
        />
      </div>
    </div>

    <div class="relationship-tabs-panel card overflow-hidden">
      <div class="flex flex-row">
        <div
          class="py-5 px-8 border-b-2 focus:outline-none tab cursor-pointer"
          :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40', errorTabs.indexOf(tab.name) >= 0 ? 'text-error' : '' ]"
          v-for="(tab, key) in tabs"
          :key="key"
          @click="handleTabClick(tab, $event)"
        >{{ tab.name }}</div>
        <div class="flex-1 border-b-2 border-40"></div>
      </div>
      <div
        v-for="(tab, index) in tabs"
        v-show="tab.name == activeTab"
        :label="tab.name"
        :key="'related-tabs-fields' + index"
      >
        <div :class="{'px-6 py-3':!tab.listable}">
          <component
            v-for="(field, index) in tab.fields"
            :class="{'remove-bottom-border': index == tab.fields.length - 1}"
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
  InteractsWithResourceInformation
} from "laravel-nova";

export default {
  mixins: [
    HandlesValidationErrors,
    FormField,
    InteractsWithResourceInformation
  ],
  props: [
    "resource",
    "resourceName",
    "resourceId",
    "field",
    "errors",
    "viaResource",
    "viaRelationship",
    "viaResourceId"
  ],
  data() {
    return {
      tabs: null,
      activeTab: "",
      errorTabs: [],
    };
  },
  computed: {
    fieldsOutsideTabs() {
      return [];
    }
  },
  watch: {
    errors: {
      handler(errors) {
        const errorFields = Object.keys(errors.errors);
        const errorTabs = [];
        let goToTab = false;
        // If there were validation errors
        if(errorFields.length > 0) {
          // check which tabs have an error
          _.forEach(this.field.fields, field => {
            if (typeof field.tab === 'undefined') return;

            if (errorFields.indexOf(field.attribute) >= 0 && errorTabs.indexOf(field.tab) === -1) {
              errorTabs.push(field.tab);
            }
          });
        }

        // If there were tabs with errors
        if(errorTabs.length > 0) {
          // Go to the first tab that errors
          Object.keys(this.tabs).forEach(tab => {
            if(goToTab !== false) return;
            if(errorTabs.indexOf(tab) >= 0) {
              goToTab = tab;
            }
          });

          // Go to the first errored tab, if any
          if(goToTab !== false) {
              this.handleTabClick(this.tabs[goToTab]);
          }
        }

        // Store the error tabs
        this.errorTabs = errorTabs;
      },
      deep: true
    }
  },
  mounted() {
    let tabs = {};
    _.toArray(this.field.fields).forEach(field => {
      if (!field.tab) {
        return this.fieldsOutsideTabs.push(field);
      }
      if (!tabs.hasOwnProperty(field.tab)) {
        tabs[field.tab] = {
          name: field.tab,
          listable: field.listableTab,
          fields: []
        };
      }
      tabs[field.tab].fields.push(field);
    });
    this.tabs = tabs;
    this.handleTabClick(tabs[Object.keys(tabs)[0]]);
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
      this.$emit("actionExecuted");
    },
    handleTabClick(tab, event) {
      this.activeTab = tab.name;
    }
  }
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
