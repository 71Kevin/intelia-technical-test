import { createApp } from 'vue';
import SignUpForm from './components/SignUpForm.vue';
import VueMask from '@devindex/vue-mask';

function initializeApp() {
    const app = createApp(SignUpForm);
    app.use(VueMask);
    app.mount('#vue-app');
}

initializeApp();
