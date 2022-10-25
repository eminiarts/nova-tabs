<template>
  <div :class="darkModeClass">
    <div class="tab-group">
      <slot>
        <Heading v-if="panel.showTitle" :level="1" v-text="panel.name"/>

        <p
          v-if="panel.helpText"
          :class="panel.helpText ? 'tabs-mt-2' : 'tabs-mt-3'"
          class="tabs-text-gray-500 tabs-text-sm tabs-font-semibold tabs-italic"
          v-html="panel.helpText"
        ></p>
      </slot>

      <div class="tab-card"
           :class="[
          panel.showTitle && !panel.showToolbar ? 'tabs-mt-3' : ''
        ]"
      >
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
            <KeepAlive>
              <template
                v-for="(field, index) in tab.fields"
                :key="'tab-' + index"
              >
                <component
                  v-if="!field.from"
                  :is="getComponentName(field)"
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

                <component
                    v-if="field.from"
                    :is="getComponentName(field)"
                    :errors="validationErrors"
                    :resource-id="getResourceId(field)"
                    :resource-name="field.resourceName"
                    :field="field"
                    :via-resource="field.from.viaResource"
                    :via-resource-id="field.from.viaResourceId"
                    :via-relationship="field.from.viaRelationship"
                    :form-unique-id="relationFormUniqueId"
                    @field-changed="$emit('field-changed')"
                    @file-deleted="$emit('update-last-retrieved-at-timestamp')"
                    @file-upload-started="$emit('file-upload-started')"
                    @file-upload-finished="$emit('file-upload-finished')"
                    :show-help-text="field.helpText != null"
                />
              </template>
            </KeepAlive>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BehavesAsPanel from '../mixins/BehavesAsPanel';
import HasTabs from "../mixins/HasTabs";

export default {
  mixins: [BehavesAsPanel, HasTabs],
  data: () => ({
    tabMode: 'form',
  }),
};
</script>
