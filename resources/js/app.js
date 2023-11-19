import './bootstrap';

import { createApp } from 'vue';
import App from '@/js/src/App.vue';

import '@/assets/css/portal.css';
import '@/assets/plugins/fontawesome/js/all.min.js';

import Router from '@/js/src/router/router.js';

createApp(App).use(Router).mount('#app');
