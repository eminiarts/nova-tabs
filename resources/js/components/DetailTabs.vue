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
                  :dusk="tab.slug + '-tab'"
                  :class="getIsTabCurrent(tab) ? 'active tabs-text-' + getCurrentColor() + '-500 tabs-font-bold tabs-border-b-2 tabs-border-b-' + getCurrentColor() + '-500' : 'tabs-text-gray-600 hover:tabs-text-gray-800 dark:tabs-text-gray-400 hover:dark:tabs-text-gray-200'"
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
          <div class="divide-y divide-gray-100 dark:divide-gray-700" :class="getBodyClass(tab)">
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
import BehavesAsPanel from '../mixins/BehavesAsPanel';
import HasTabs from "../mixins/HasTabs";

export default {
  mixins: [BehavesAsPanel, HasTabs],
  data: () => ({
    tabMode: 'detail',
  }),
};
</script>
