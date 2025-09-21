import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';


// Import your BlogManagement component
import BlogManagement from './Pages/BlogManagement.vue';

// Create Vuetify instance
const vuetify = createVuetify({
    components,
    directives,
});

// Create Vue app and register Vuetify
const app = createApp(BlogManagement);
app.use(vuetify);

// Mount the app
app.mount('#app');
