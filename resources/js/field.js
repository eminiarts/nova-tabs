import DetailTabs from './components/DetailTabs';
import FormTabs from './components/FormTabs';
import PerfectScrollbar from 'vue2-perfect-scrollbar';
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css';

Nova.booting(Vue => {
    Vue.use(PerfectScrollbar);
    Vue.component('detail-tabs', DetailTabs);
    Vue.component('form-tabs', FormTabs);
});
