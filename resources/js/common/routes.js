import Index from '../components/IndexPage';
import About from '../components/AboutPage';
import Main from '../components/Main';
import { createRouter, createWebHistory } from 'vue-router'
import DiscussionPage from "../components/DiscussionPage";

export default function(app) {
    const routes = [
        { path: '/', component: Index },
        { path: '/about', component: About },
        { path: '/d/:id', component: DiscussionPage, props: true },
    ];

    app.main = Main;

    app.router = createRouter({
        history: createWebHistory(),
        routes,
    });
}
