<template>
  <div>
    <slot>
      <!--<h4 class="text-90 font-normal text-2xl mb-3">{{ panel.name }}</h4>-->
    </slot>
    <div class="relationship-tabs-panel card">
      <div class="tabs-wrap border-b-2 border-40 w-full">
        <div class="tabs flex flex-row overflow-x-auto">
          <button
            class="py-5 px-8 border-b-2 focus:outline-none tab"
            :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40']"
            v-for="(tab, key) in tabs"
            :key="key"
            @click="handleTabClick(tab, $event)"
          >{{ tab.name }}</button>
        </div>
      </div>
      <div
        :class="[(panel && panel.defaultSearch) ? 'default-search': 'tab-content', slugify(tab.name)]"
        :ref="slugify(tab.name)"
        v-for="(tab, index) in tabs"
        v-show="tab.name == activeTab"
        :label="tab.name"
        :key="'related-tabs-fields' + index"
      >
        <div :class="{
            'px-6 py-3':!tab.listable,
            'flex flex-wrap' : tab.fields.some((field) => field.gridder),
        }" v-if="tab.init">
          <component
            v-for="(field, index) in tab.fields"
            :class="componentClass(tab, field, index)"
            :key="'tab-' + index"
            :is="componentName(field)"
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
    activeTabSearchWidth() {
      if (this.activeTabHasSearch) {
        let element = this.$el.querySelector(
          "." + this.slugify(this.activeTab) + " .w-search"
        );
      }
    },
    activeTabHasSearch() {
      let tab = _.find(this.tabs, { name: this.activeTab });
      let hasSearch = false;

      if (!tab || this.panel.defaultSearch) {
        return false;
      }

      _.forEach(tab.fields, function(field) {
        if (field.resourceName == "action-events") {
          return;
        }
        if (
          field.component == "has-many-field" ||
          field.component == "belongs-to-many-field" ||
          field.component == "morph-many-field" ||
          field.component == "morph-to-many-field"
        ) {
          hasSearch = true;
        }
      });

      return hasSearch;
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
    if(!_.isUndefined(this.$route.query.tab)) {
        if(_.isUndefined(tabs[this.$route.query.tab])) {
            this.handleTabClick(tabs[Object.keys(tabs)[0]]);
        } else {
            this.activeTab = this.$route.query.tab;
            this.handleTabClick(tabs[this.$route.query.tab]);
        }
    } else {
        this.handleTabClick(tabs[Object.keys(tabs)[0]]);
    }
  },
  methods: {
    /**
     * Get component class
     */
    componentClass(tab, field, index) {
        let gridderClass = field.gridder ? field.gridder.panelSize : 'w-full';
        let borderBottom = index == tab.fields.length - 1 ? ' remove-bottom-border' : ' ';
        return gridderClass + borderBottom
    },
    /**
     * Handle the actionExecuted event and pass it up the chain.
     */
    actionExecuted() {
      this.$emit("actionExecuted");
    },
    handleTabClick(tab, event) {
      tab.init = true;
      this.activeTab = tab.name;
    },
    /**
     * Slugify
     * From: https://gist.github.com/mathewbyrne/1280286
     */
    slugify(text) {
      return text
        .toString()
        .toLowerCase()
        .replace(/\s+/g, "-") // Replace spaces with -
        .replace(/[^\w\-]+/g, "") // Remove all non-word chars
        .replace(/\-\-+/g, "-") // Replace multiple - with single -
        .replace(/^-+/, "") // Trim - from start of text
        .replace(/-+$/, ""); // Trim - from end of text
    },
    componentName(field) {
      return field.prefixComponent
        ? "detail-" + field.component
        : field.component;
    }
  }
};
</script>


<style lang="scss">
.relationship-tabs-panel {
  .has-search-bar {
  }
  .tabs::-webkit-scrollbar {
    height: 8px;
    border-radius: 4px;
  }
  .tabs::-webkit-scrollbar-thumb {
    background: #cacaca;
  }
  .tabs {
    white-space: nowrap;
    margin-bottom: -2px;
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
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    margin-top: 0.75rem;
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
    z-index: 1;

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
