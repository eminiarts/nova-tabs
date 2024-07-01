<template>
  <div :class="darkModeClass">
    <div class="tab-group">
      <slot>
        <Heading
          v-if="panel.showTitle"
          :level="1"
          :class="panel.helpText ? 'mb-2' : 'mb-3'"
        >
          {{ panel.name }}
        </Heading>

        <p
          v-if="panel.helpText"
          class="text-gray-500 text-sm font-semibold italic mb-3"
          v-html="panel.helpText"
        />
      </slot>

      <div class="tab-card">
        <div id="tabs">
          <div class="block">
            <nav
              aria-label="Tabs"
              class="tab-menu"
            >
              <a
                v-for="(tab, key) in getSortedTabs(tabs)"
                :key="key"
                :class="getIsTabCurrent(tab) ? 'active tabs-text-' + getCurrentColor() + '-500 tabs-font-bold tabs-border-b-2 tabs-border-b-' + getCurrentColor() + '-500' : 'tabs-text-gray-600 hover:tabs-text-gray-800 dark:tabs-text-gray-400 hover:dark:tabs-text-gray-200'"
                :dusk="tab.slug + '-tab'"
                :ref="tab.slug + '-tab'"
                class="tab-item border-gray-200"
                @click.prevent="handleTabClick(tab)"
              >
                <span class="capitalize">{{ tab.properties.title }}</span>
              </a>
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
          <div class="divide-y divide-gray-100 dark:divide-gray-700" :class="getBodyClass(tab)">
            <component
              v-for="(field, index) in tab.fields"
              :index="index"
              :key="index"
              :is="`form-${field.component}`"
              :errors="validationErrors"
              :resource-id="resourceId"
              :resource-name="resourceName"
              :related-resource-name="relatedResourceName"
              :related-resource-id="relatedResourceId"
              :field="field"
              :via-resource="viaResource"
              :via-resource-id="viaResourceId"
              :via-relationship="viaRelationship"
              :shown-via-new-relation-modal="shownViaNewRelationModal"
              :form-unique-id="formUniqueId"
              :mode="mode"
              @field-shown="handleFieldShown"
              @field-hidden="handleFieldHidden"
              @field-changed="$emit('field-changed')"
              @file-deleted="handleFileDeleted"
              @file-upload-started="$emit('file-upload-started')"
              @file-upload-finished="$emit('file-upload-finished')"
              :show-help-text="showHelpText"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {BehavesAsPanel, HasTabs, HandlesPanelVisibility} from "../mixins";

export default {
  mixins: [BehavesAsPanel, HasTabs, HandlesPanelVisibility],

  props: {
    shownViaNewRelationModal: { type: Boolean, default: false },
    showHelpText: { type: Boolean, default: false },
    panel: { type: Object, required: true },
    name: { default: 'Panel' },
    dusk: { type: String },
    fields: { type: Array, default: [] },
    formUniqueId: { type: String },
    validationErrors: { type: Object, required: true },
    resourceName: { type: String, required: true },
    resourceId: { type: [Number, String] },
    relatedResourceName: { type: String },
    relatedResourceId: { type: [Number, String] },
    viaResource: { type: String },
    viaResourceId: { type: [Number, String] },
    viaRelationship: { type: String },
  },

  data: () => ({
    tabMode: 'form',
  }),

  methods: {
    handleFileDeleted() {
      this.$emit('update-last-retrieved-at-timestamp')
    },
  },
};
</script>
