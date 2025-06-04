import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css';
import 'datatables.net-buttons/js/buttons.colVis';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import $ from 'jquery';
import jszip from 'jszip';
import pdfmake from 'pdfmake/build/pdfmake';
import vfsFonts from 'pdfmake/build/vfs_fonts';
import CompaniesComponent from './components/CompaniesComponent.vue';
import EmployeesComponent from './components/EmployeesComponent.vue';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';

// Make jQuery and dependencies available globally
window.$ = window.jQuery = $;
window.JSZip = jszip;
window.pdfMake = pdfmake;

// Configure pdfMake fonts
pdfmake.vfs = vfsFonts.pdfMake.vfs;
pdfmake.fonts = {
    Roboto: {
        normal: 'Roboto-Regular.ttf',
        bold: 'Roboto-Medium.ttf',
        italics: 'Roboto-Italic.ttf',
        bolditalics: 'Roboto-MediumItalic.ttf'
    }
};

const routes = [
    {
        path: '/',
        name: 'companies',
        component: CompaniesComponent,
    },
    {
        path: '/employees',
        name: 'Employees',
        component: EmployeesComponent,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp({});
app.use(VueAxios, axios);
app.use(router);
app.component('companies-component', CompaniesComponent);
app.component('employees-component', EmployeesComponent);

app.mount('#app');
