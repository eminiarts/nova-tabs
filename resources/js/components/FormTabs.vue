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
                :class="getIsTabCurrent(tab) ? 'active text-primary-500' : 'tabs-text-gray-800 dark:tabs-text-gray-50'"
                :dusk="tab.slug + '-tab'"
                class="tab-item"
                @click.prevent="handleTabClick(tab)"
              >
                <span class="capitalize">{{ tab.properties.title }}</span>
                <span
                  v-if="getIsTabCurrent(tab)"
                  aria-hidden="true"
                  class="bg-primary-500 tabs-absolute tabs-inset-x-0 tabs-bottom-0 tabs-h-0.5"
                ></span>
                <span
                  v-else
                  aria-hidden="true"
                  class="tabs-bg-transparent tabs-absolute tabs-inset-x-0 tabs-bottom-0 tabs-h-0.5"
                ></span>
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
          <div :class="getBodyClass(tab)">
            <KeepAlive>
              <div
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
              </div>
            </KeepAlive>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BehavesAsPanel from '../../../vendor/laravel/nova/resources/js/mixins/BehavesAsPanel';
import HasTabs from "../mixins/HasTabs";

import Heading from '../../../vendor/laravel/nova/resources/js/components/Heading.vue';
import Card from '../../../vendor/laravel/nova/resources/js/components/Card.vue';

export default {
  mixins: [BehavesAsPanel, HasTabs],
  components: {Card, Heading},
  props: {
    mode: {
      type: String,
      default: 'form',
    },
  }
};
</script>
