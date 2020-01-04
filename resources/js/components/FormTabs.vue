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

    <div class="relationship-tabs-panel card w-full overflow-hidden">
      <div class="flex flex-row">
        <div
          class="py-5 px-8 border-b-2 focus:outline-none tab cursor-pointer"
          :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40', tabHasErrors(tab) ? 'text-error' : '' ]"
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
      activeTab: ""
    };
  },
  computed: {
    fieldsOutsideTabs() {
      return [];
    }
  },
  watch: {
    errors: {
      handler: function(errors) {
        let vm = this;
        let goToTab = false;
        Object.keys(vm.tabs).forEach(function(key) {
          if (vm.tabHasErrors(vm.tabs[key]) && !goToTab) {
            goToTab = true;
            return vm.handleTabClick(vm.tabs[key]);
          }
        });
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
    },

    tabHasErrors(tab) {
      let hasErrors = false;
      let vm = this;

      Object.keys(this.errors.errors).forEach(function(key) {
        if (_.includes(tab.fields.map(o => o["attribute"]), key)) {
          hasErrors = true;
        }
      });

      tab.hasErrors = hasErrors;

      return hasErrors;
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
