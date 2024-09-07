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
                @click.prevent="tabClicked(tab)"
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
                  :errors="isResourceCreatePage() ? ourErrors : validationErrors"
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
                    :errors="isResourceCreatePage() ? ourErrors : validationErrors"
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

  <div
    v-if="this.panel.wizardFormEnabled && this.isResourceCreatePage()"
    class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 md:space-x-3">
    <button
      v-if="currentStep > 0"
      @click="prevStep"
      type="button"
      class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center bg-transparent border-transparent h-9 px-3 text-gray-600 dark:text-gray-400 hover:[&amp;:not(:disabled)]:bg-gray-700/5 dark:hover:[&amp;:not(:disabled)]:bg-gray-950"
      dusk="prev-step-button">
        <span class="flex items-center gap-1">Previus</span>
      </button>

    <button
      v-if="currentStep < steps.length - 1"
      @click="nextStep"
      type="button"
      class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&amp;:not(:disabled)]:bg-primary-400 hover:[&amp;:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
      dusk="next-step-button">
        <span class="flex items-center gap-1">Next</span>
    </button>

    <button
      v-if="currentStep === steps.length - 1"
      type="submit"
      class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&amp;:not(:disabled)]:bg-primary-400 hover:[&amp;:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
      dusk="next-step-button">
        <span class="flex items-center gap-1">Submit</span>
    </button>
  </div>
</template>

<script>
import each from 'lodash/each';
import isNil from 'lodash/isNil';
import tap from 'lodash/tap';
import { HandlesFormRequest } from 'laravel-nova';
import { Errors } from 'form-backend-validation'
import BehavesAsPanel from '../mixins/BehavesAsPanel';
import HasTabs from "../mixins/HasTabs";

export default {
  mixins: [BehavesAsPanel, HasTabs, HandlesFormRequest],

  data: () => ({
    tabMode: 'form',

    ourErrors: new Errors(),

    novaFormButtons: {
      createAndAddOther: {},
      cancel: {},
      submit: {},
    },

    steps: [],
    currentStep: 0,
  }),

  methods: {
    tabClicked(tab) {
      this.handleTabClick(tab)

      if (true) {}
      this.currentStep = this.steps.findIndex(item => item.slug === this.selectedTab.slug);
    },

    nextStep() {
      if (this.currentStep < this.steps.length - 1) {
        let validations = [];

        Array.from(this.selectedTab.fields).forEach(field => {
          validations.push(field.validationRules)
        })

        Nova.request().post(`/nova-vendor/eminiarts/nova-tabs/validate`, this.createResourceFormData(validations))
          .then(response => {
            if (response.data.message === 'Yeah') {
              this.currentStep++;

              this.handleTabClick(this.steps[this.currentStep])
            }
          }).catch(error => {
            this.ourErrors = new Errors(error.response.data.errors)
          })
      }
    },

    /**
     * Create the form data for creating the resource.
     */
    createResourceFormData(validations) {
      return tap(new FormData(), formData => {
        // append validations
        formData.append('validations', JSON.stringify(validations))

        each(this.selectedTab.fields, field => {
          field.fill(formData)
        })

        if (!isNil(this.fromResourceId)) {
          formData.append('fromResourceId', this.fromResourceId)
        }

        formData.append('viaResource', this.viaResource)
        formData.append('viaResourceId', this.viaResourceId)
        formData.append('viaRelationship', this.viaRelationship)
      })
    },

    prevStep() {
      if (this.currentStep > 0) {
        this.currentStep--;

        this.handleTabClick(this.steps[this.currentStep])
      }
    },

    hideNovaFormButtons() {
      this.novaFormButtons.cancel = document.querySelector('button[dusk="cancel-create-button"]');
      this.novaFormButtons.submit = document.querySelector('button[dusk="create-button"]');
      this.novaFormButtons.createAndAddOther = document.querySelector('button[dusk="create-and-add-another-button"]');

      this.novaFormButtons.cancel.style.display = 'none';
      this.novaFormButtons.submit.style.display = 'none';
      this.novaFormButtons.createAndAddOther.style.display = 'none';
    },

    isResourceCreatePage() {
      return Nova.$router.page.component === 'Nova.Create'
    }
  },

  mounted() {
    if (this.panel.wizardFormEnabled && this.isResourceCreatePage()) {
      this.hideNovaFormButtons();

      this.steps = this.getSortedTabs(this.tabs);

      this.currentStep = this.steps.findIndex(item => item.slug === this.selectedTab.slug);
    }
  }
};
</script>
