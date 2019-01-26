<template>
  <div>
    FORM TABS
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