import { createApp } from 'vue';

export const mountApp = (el, app) => {
    app.vue = createApp(app.main).use(app.router).mount(el);
};

export const mount = (el, component) => {
    return createApp(component).mount(el);
};
