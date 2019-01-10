<template>
  <div>
    <div class="relationship-tabs-panel card overflow-hidden">
      <div class="flex flex-row">
        <button
          class="py-5 px-8 border-b-2 focus:outline-none tab"
          :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40']"
          v-for="(tab, key) in tabs"
          :key="key"
          @click="activeTab=tab.name"
        >{{ tab.name }}</button>
        <div class="flex-1 border-b-2 border-40"></div>
      </div>

      <div
        class
        :class="[(field.extraAttributes && field.extraAttributes.defaultSearch) ? 'default-search': 'tab-content']"
        v-for="(tab, index) in tabs"
        v-show="tab.name == activeTab"
        :label="tab.name"
        :key="'related-tabs-fields' + index"
      >
        <div v-if="tab.fields.length > 1" class="px-6 py-3">
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
        <div v-else>
          <component
            :is="'detail-' + tab.fields.component"
            :resource-name="resourceName"
            :resource-id="resourceId"
            :resource="resource"
            :field="tab.fields"
            @actionExecuted="actionExecuted"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  data() {
    return {
      tabs: null,
      activeTab: ""
    };
  },
  mounted() {
    this.tabs = this.field.tabs;
    this.activeTab = this.tabs[0].name;
  },
  methods: {
    /**
     * Handle the actionExecuted event and pass it up the chain.
     */
    actionExecuted() {
      this.$emit("actionExecuted");
    },
    handleTabClick(tab, event) {}
  }
};
</script>

<style lang="scss">
.relationship-tabs-panel {
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