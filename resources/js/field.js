import DetailTabs from './components/DetailTabs.vue';
import FormTabs from './components/FormTabs.vue';

Nova.booting(Vue => {
    Vue.component('detail-tabs', DetailTabs);
    Vue.component('form-tabs', FormTabs);
});
