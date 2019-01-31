<template>
  <div>
    <slot>
      <!--<h4 class="text-90 font-normal text-2xl mb-3">{{ panel.name }}</h4>-->
    </slot>
    <div class="relationship-tabs-panel card overflow-hidden overflow-x-auto">
      <div
        class="tabs flex flex-row overflow-x-auto"
        :class="{'has-search-bar': activeTabHasSearch}"
      >
        <button
          class="py-5 px-8 border-b-2 focus:outline-none tab"
          :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40']"
          v-for="(tab, key) in tabs"
          :key="key"
          @click="handleTabClick(tab, $event)"
        >{{ tab.name }}</button>
        <div class="flex-1 border-b-2 border-40"></div>
      </div>
      <div
        :class="[(panel && panel.defaultSearch) ? 'default-search': 'tab-content']"
        v-for="(tab, index) in tabs"
        v-if="tab.init"
        v-show="tab.name == activeTab"
        :label="tab.name"
        :key="'related-tabs-fields' + index"
      >
        <div :class="{'px-6 py-3':!tab.listable}">
          <component
            v-for="(field, index) in tab.fields"
            :class="{'remove-bottom-border': index == tab.fields.length - 1}"
            :key="'tab-' + index"
            :is="'detail-' + field.component"
            :resource-name="resourceName"
            :resource-id="resourceId"
            :resource="resource"
            :field="field"
            @actionExecuted="actionExecuted"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BehavesAsPanel from "laravel-nova/src/mixins/BehavesAsPanel";
export default {
  mixins: [BehavesAsPanel],
  data() {
    return {
      tabs: null,
      activeTab: ""
    };
  },
  computed: {
    activeTabHasSearch() {
      let tab = _.find(this.tabs, { name: this.activeTab });
      let hasTab = false;

      if (!tab || this.panel.defaultSearch) {
        return false;
      }

      _.forEach(tab.fields, function(field) {
        if (field.resourceName == "action-events") {
          return;
        }
        if (
          field.component == "has-many-field" ||
          field.component == "has-oney-field" ||
          field.component == "belongs-to-field" ||
          field.component == "belongs-to-many-field" ||
          field.component == "morph-many-field" ||
          field.component == "morph-to-field" ||
          field.component == "morph-to-many-field"
        ) {
          hasTab = true;
        }
      });

      return hasTab;
    }
  },
  mounted() {
    let tabs = {};
    this.panel.fields.forEach(field => {
      if (!tabs.hasOwnProperty(field.tab)) {
        tabs[field.tab] = {
          name: field.tab,
          init: false,
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
     * Handle the actionExecuted event and pass it up the chain.
     */
    actionExecuted() {
      this.$emit("actionExecuted");
    },
    handleTabClick(tab, event) {
      tab.init = true;
      this.activeTab = tab.name;
    }
  }
};
</script>


<style lang="scss">
.relationship-tabs-panel {
  .has-search-bar {
    margin-right: 45%;
  }
  .tabs::-webkit-scrollbar {
    width: 5px;
    height: 5px;
  }
  .tabs::-webkit-scrollbar-thumb {
    background: #eef1f4;
  }
  .tabs {
    white-space: nowrap;
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

  .default-search > div > .relative > .flex {
    justify-content: flex-end;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    margin-top: 1.25rem;
    margin-bottom: 0;
    > .mb-6 {
      margin-bottom: 0;
    }
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