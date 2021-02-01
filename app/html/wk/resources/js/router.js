import Vue from 'vue';
import VueRouter from 'vue-router';

import MenuComponent from './components/MenuComponent.vue';
import ImageRekognitionComponent from './components/ImageRekognitionComponent';

Vue.use(VueRouter);

const router = {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: MenuComponent
        },
        {
            path: '/imageRekognition',
            name: 'imageRekognition',
            component: ImageRekognitionComponent
        },
    ]
};

export default new VueRouter(router);
