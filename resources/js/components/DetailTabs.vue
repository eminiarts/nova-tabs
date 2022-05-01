<template>
  <div :class="darkModeClass">
    <div class="tab-group">
      <slot>
        <Heading :level="1" v-text="panel.name" v-if="panel.showTitle"/>

        <p
            v-if="panel.helpText"
            :class="panel.helpText ? 'tabs-mt-2' : 'tabs-mt-3'"
            class="tabs-text-gray-500 tabs-text-sm tabs-font-semibold tabs-italic"
            v-html="panel.helpText"
        ></p>
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
                  :dusk="tab.slug + '-tab'"
                  :class="getIsTabCurrent(tab) ? 'active text-primary-500 tabs-font-bold' : 'tabs-text-gray-600 hover:tabs-text-gray-800 dark:tabs-text-gray-400 hover:dark:tabs-text-gray-200'"
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
            :key="'related-tabs-fields' + index"
            :ref="getTabRefName(tab)"
            :class="[
                        'tab',
                        tab.slug,
                        tab.classes
                    ]"
            :label="tab.name"
            v-show="getIsTabCurrent(tab)"
        >
          <div :class="getBodyClass(tab)">
            <KeepAlive v-for="(field, index) in tab.fields" :key="index">
              <component
                  :is="getComponentName(field)"
                  :class="{'remove-bottom-border': index === tab.fields.length - 1}"
                  :field="field"
                  :index="index"
                  :resource="resource"
                  :resource-id="resourceId"
                  :resource-name="resourceName"
                  @actionExecuted="actionExecuted"
              />
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
      default: 'detail',
    },
  }
};
</script>
