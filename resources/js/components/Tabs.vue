<template>
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
    class=""
    :class="[(field.extraAttributes && field.extraAttributes.defaultSearch) ? 'default-search': 'tab-content']"
    v-for="(tab, index) in tabs"
    v-show="tab.name == activeTab"
    :label="tab.name || tab.field.resourceName.toLocaleUpperCase()"
    :key="'related-tabs-fields' + index"
    :name="tab.field.resourceName">
    <resource-index
    class="resource-index-holder"
    :resource-name="tab.field.resourceName"
    :via-resource="resourceName"
    :via-resource-id="resourceId"
    :via-relationship="tab.targetRelation"
    :relationship-type="tab.relationType"
    @actionExecuted="actionExecuted"
    :load-cards="false"
    />
  </div>
</div>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
      return {
        tabs: null,
        activeTab: ''
      }
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
          this.$emit('actionExecuted')
        },
        handleTabClick(tab, event) {
        }
      },
    }
  </script>
  <style lang="scss">
  .relationship-tabs-panel {
    .card {
      box-shadow: none;
    }

    h1 {
      display: none;
    }

    .tab  {
      padding-top: 1.25rem;
      padding-bottom: 1.25rem;
    }

    > .default-search > .relative > .flex {
      justify-content: flex-end;
      padding-left: 1.25rem;
      padding-right: 1.25rem;
      margin-top: 1.25rem;
      margin-bottom: 0;
    }

    > .tab-content > .relative > .flex {
      justify-content: flex-end;
      padding-left: 0.75rem;
      padding-right: 0.75rem;

      position: absolute;
      top: 0;
      right: 0;
      z-index: 55;
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