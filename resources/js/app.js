import './bootstrap'
import { createApp  } from 'vue';

import app from './components/app.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

const App = createApp(app);

App.use(router);
App.use(vuetify);
App.mount('#app');