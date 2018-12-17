<template>
  <div class="card overflow-hidden">
    <slot>
      <!-- <h4 class="text-90 font-normal text-2xl mb-3">{{ panel.name }}</h4> -->
      <div class="flex flex-row">
        <button
          class="py-5 px-8 border-b-2 focus:outline-none tab"
          :class="[activeTab == tab ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40']"
          v-for="(tab, key) in tabs"
          :key="key"
          @click="activeTab=tab"
        >{{ tab }}</button>

        <div class="flex-1 border-b-2 border-40"></div>
      </div>
    </slot>

    <card
      class="py-3 px-6"
      v-for="(fields, key) in groupedFields"
      :key="key"
      v-if="activeTab == key"
    >
      <component
        :class="{'remove-bottom-border': index == fields.length - 1}"
        :key="index"
        v-for="(field, index) in fields"
        :is="resolveComponentName(field)"
        :resource-name="resourceName"
        :resource-id="resourceId"
        :resource="resource"
        :field="field"
        @actionExecuted="actionExecuted"
      />
    </card>
  </div>
</template>

<script>
import { BehavesAsPanel } from "laravel-nova";

export default {
  mixins: [BehavesAsPanel],

  mounted() {
    this.activeTab = this.tabs[0];
  },

  data() {
    return {
      activeTab: null
    };
  },

  computed: {
    tabs() {
      return _.uniq(this.panel.fields.map(o => o["tab"]));
    },
    groupedFields() {
      return _.groupBy(this.panel.fields, "tab");
    }
  },

  methods: {
    /**
     * Resolve the component name.
     */
    resolveComponentName(field) {
      return field.prefixComponent
        ? "detail-" + field.component
        : field.component;
    }
  }
};
</script>

<style lang="scss">
.card {
  .card {
    box-shadow: none;
  }
  .tab {
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
  }
}
</style>