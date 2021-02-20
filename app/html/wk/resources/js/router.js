import Vue from 'vue';
import VueRouter from 'vue-router';

import MenuComponent from './components/MenuComponent.vue';
import ImageRekognitionDetectComponent from './components/ImageRekognition/ImageRekognitionDetectComponent';
import ImageRekognitionResultComponent from './components/ImageRekognition/ImageRekognitionResultComponent';

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
            path: '/imageRekognition/detect',
            name: 'imageRekognitionDetect',
            component: ImageRekognitionDetectComponent,
        },
        {
            path: '/imageRekognition/result',
            name: 'imageRekognitionResult',
            component: ImageRekognitionResultComponent,
        },
    ]
};

export default new VueRouter(router);
