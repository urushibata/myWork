import Vue from 'vue';
import VueRouter from 'vue-router';

import MenuComponent from '@/components/MenuComponent';
import ImageRekognitionDetectComponent from '@/components/ImageRekognition/ImageRekognitionDetectComponent';
import ImageRekognitionResultComponent from '@/components/ImageRekognition/ImageRekognitionResultComponent';
import PdfSortComponent from '@/components/PdfSort/PdfSortComponent';


Vue.use(VueRouter);

const router = {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: MenuComponent,
            meta: {
                breadcrumb: 'メニュー',
            },
        },
        {
            path: '/imageRekognition/detect',
            name: 'imageRekognitionDetect',
            component: ImageRekognitionDetectComponent,
            meta: {
                breadcrumb:
                {
                    label: '画像解析',
                    parent: 'index'
                }
            },
        },
        {
            path: '/imageRekognition/result',
            name: 'imageRekognitionResult',
            component: ImageRekognitionResultComponent,
            meta: {
                breadcrumb:
                {
                    label: '画像解析結果',
                    parent: 'index'
                }
            },
        },
        {
            path: '/pdfSort/',
            name: 'PdfSort',
            component: PdfSortComponent,
            meta: {
                breadcrumb:
                {
                    label: 'PDFファイル並び変え',
                    parent: 'index'
                }
            },
        },
    ]
};

export default new VueRouter(router);
