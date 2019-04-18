<template>
  <div>
    <slot>
      <!--<h4 class="text-90 font-normal text-2xl mb-3">{{ panel.name }}</h4>-->
    </slot>
    <div class="relationship-tabs-panel card">
      <div class="tabs-wrap border-b-2 border-40 w-full">
        <div class="tabs flex flex-row overflow-x-auto items-center">
         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" class="w-3 h-3 fill-current text-grey cursor-pointer hover:text-primary" viewBox="0 0 444.531 444.531" style="enable-background:new 0 0 444.531 444.531;" xml:space="preserve">
<g>
	<path d="M213.13,222.409L351.88,83.653c7.05-7.043,10.567-15.657,10.567-25.841c0-10.183-3.518-18.793-10.567-25.835   l-21.409-21.416C323.432,3.521,314.817,0,304.637,0s-18.791,3.521-25.841,10.561L92.649,196.425   c-7.044,7.043-10.566,15.656-10.566,25.841s3.521,18.791,10.566,25.837l186.146,185.864c7.05,7.043,15.66,10.564,25.841,10.564   s18.795-3.521,25.834-10.564l21.409-21.412c7.05-7.039,10.567-15.604,10.567-25.697c0-10.085-3.518-18.746-10.567-25.978   L213.13,222.409z"/>
</g>

</svg>
          <button
            class="py-5 px-8 border-b-2 focus:outline-none tab"
            :class="[activeTab == tab.name ? 'text-grey-black font-bold border-primary': 'text-grey font-semibold border-40']"
            v-for="(tab, key) in tabs"
            :key="key"
            @click="handleTabClick(tab, $event)"
          >{{ tab.name }}</button>

 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" class="w-3 h-3 fill-current text-grey cursor-pointer hover:text-primary" viewBox="0 0 444.819 444.819" style="enable-background:new 0 0 444.819 444.819;" xml:space="preserve">
<g>
	<path d="M352.025,196.712L165.884,10.848C159.029,3.615,150.469,0,140.187,0c-10.282,0-18.842,3.619-25.697,10.848L92.792,32.264   c-7.044,7.043-10.566,15.604-10.566,25.692c0,9.897,3.521,18.56,10.566,25.981l138.753,138.473L92.786,361.168   c-7.042,7.043-10.564,15.604-10.564,25.693c0,9.896,3.521,18.562,10.564,25.98l21.7,21.413   c7.043,7.043,15.612,10.564,25.697,10.564c10.089,0,18.656-3.521,25.697-10.564l186.145-185.864   c7.046-7.423,10.571-16.084,10.571-25.981C362.597,212.321,359.071,203.755,352.025,196.712z"/>
</g>

</svg>

        </div>
      </div>
      <div
        :class="[(panel && panel.defaultSearch) ? 'default-search': 'tab-content', slugify(tab.name)]"
        :ref="slugify(tab.name)"
        v-for="(tab, index) in tabs"
        v-show="tab.name == activeTab"
        :label="tab.name"
        :key="'related-tabs-fields' + index"
      >
        <div :class="{'px-6 py-3':!tab.listable}" v-if="tab.init">
          <component
            v-for="(field, index) in tab.fields"
            :class="{'remove-bottom-border': index == tab.fields.length - 1}"
            :key="'tab-' + index"
            :is="componentName(field)"
            :resource-name="resourceName"
            :resource-id="resourceId"
            :resource="resource"
            :field="field"
            @actionExecuted="actionExecuted"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BehavesAsPanel from "laravel-nova/src/mixins/BehavesAsPanel";
export default {
  mixins: [BehavesAsPanel],
  data() {
    return {
      tabs: null,
      searchBarWidth: null,
      activeTab: ""
    };
  },
  computed: {
    activeTabSearchWidth() {
      if (this.activeTabHasSearch) {
        let element = this.$el.querySelector(
          "." + this.slugify(this.activeTab) + " .w-search"
        );
      }
    },
    childrenResource(){
      console.log('children');
      
      let tab = _.find(this.$children, function(o) { 
           if (!o.field) {
             return false;
           };
           return o.field.component == 'has-many-field'; 
           });

           console.log(tab.$children);

           if (tab && tab.$children) {
             return tab.$children[0].$data.resources;
           }
           return null;
    },
    activeTabContent(){

      let tab = _.find(this.tabs, { name: this.activeTab });
      let activeComponent = false;

      if (!tab || this.panel.defaultSearch) {
        return false;
      }

      _.forEach(tab.fields, function(field) {
        if (field.resourceName == "action-events") {
          return;
        }
        if (
          field.component == "has-many-field" ||
          field.component == "belongs-to-many-field" ||
          field.component == "morph-many-field" ||
          field.component == "morph-to-many-field"
        ) {
          activeComponent = field;
        }
      });

       /*  if (this.$refs[this.slugify(this.activeTab)][0].querySelector('.pl-search')) {
        console.log(activeComponent, this.$refs[this.slugify(this.activeTab)][0].querySelector('.pl-search').parentNode.parentNode, this.$refs[this.slugify(this.activeTab)][0].querySelector('.pl-search').parentNode.parentNode.clientWidth )
        
        this.searchBarWidth = this.$refs[this.slugify(this.activeTab)][0].querySelector('.pl-search').parentNode.parentNode.clientWidth;
      } else {
        this.searchBarWidth = 0;
      } */
      

      /* this.$refs[this.slugify(this.activeTab)].$nextTick(() => {
      }); */

      return activeComponent;
      return this.$refs[this.slugify(this.activeTab)].$data;
    },
    activeTabHasSearch() {
      let tab = _.find(this.tabs, { name: this.activeTab });
      let hasSearch = false;

      if (!tab || this.panel.defaultSearch) {
        return false;
      }

      _.forEach(tab.fields, function(field) {
        if (field.resourceName == "action-events") {
          return;
        }
        if (
          field.component == "has-many-field" ||
          field.component == "belongs-to-many-field" ||
          field.component == "morph-many-field" ||
          field.component == "morph-to-many-field"
        ) {
          hasSearch = true;
        }
      });

      return hasSearch;
    }
  },
  mounted() {
    let tabs = {};
    this.panel.fields.forEach(field => {
      if (!tabs.hasOwnProperty(field.tab)) {
        tabs[field.tab] = {
          name: field.tab,
          init: false,
          listable: field.listableTab,
          fields: []
        };
      }
      tabs[field.tab].fields.push(field);
    });
    this.tabs = tabs;
    this.handleTabClick(tabs[Object.keys(tabs)[0]]);

  

    
  },
  watch: {
      activeTabContent: function (val) {
         
         

       }
    },
  methods: {
    /**
     * Handle the actionExecuted event and pass it up the chain.
     */
    actionExecuted() {
      this.$emit("actionExecuted");
    },
    handleTabClick(tab, event) {
      tab.init = true;
      this.activeTab = tab.name;

      this.calculateSearchBarWidth();
    },
    calculateSearchBarWidth() {
      /* setTimeout(() => {
        let element = this.$el.querySelector(
          "." + this.slugify(this.activeTab) + " .w-search"
        );

        window.requestAnimationFrame(function() {
          document.querySelector(".tabs").style.width =
            "calc(100% - " + element.parentNode.parentNode.offsetWidth + "px)";
        });
      }, 500); */
    },
    /**
     * Slugify
     * From: https://gist.github.com/mathewbyrne/1280286
     */
    slugify(text) {
      return text
        .toString()
        .toLowerCase()
        .replace(/\s+/g, "-") // Replace spaces with -
        .replace(/[^\w\-]+/g, "") // Remove all non-word chars
        .replace(/\-\-+/g, "-") // Replace multiple - with single -
        .replace(/^-+/, "") // Trim - from start of text
        .replace(/-+$/, ""); // Trim - from end of text
    },
    componentName(field) {
      return field.prefixComponent
        ? "detail-" + field.component
        : field.component;
    }
  }
};
</script>


<style lang="scss">
.relationship-tabs-panel {
  .has-search-bar {
  }
  .tabs::-webkit-scrollbar {
    height: 8px;
    border-radius: 4px;
  }
  .tabs::-webkit-scrollbar-thumb {
    background: #cacaca;
  }
  .tabs {
    white-space: nowrap;
    margin-bottom: -2px;
  }
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
    z-index: 1;

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