Nova.booting((Vue, router) => {
    Vue.component('detail-tabs', require('./components/DetailTabs'));
    Vue.component('form-tabs', require('./components/FormTabs'));
    Vue.component('tabs-panel', require('./components/TabsPanel'));
})
