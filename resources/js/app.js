/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-resource'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/**
 *Funcionalidades axios para los productos.
*/
Vue.component('tablaproducto-component', require('./components/productos/TablaComponent.vue').default);
Vue.component('modalproducto-component', require('./components/productos/ModalComponent.vue').default);
Vue.component('modaldeleteproducto-component', require('./components/productos/ModalDeleteComponent.vue').default);
Vue.component('buscarproducto-component', require('./components/productos/BuscarComponent.vue').default);
/**
 *Funcionalidades axios para los terceros.
*/
Vue.component('tablaterceros-component', require('./components/terceros/TablaComponent.vue').default);
Vue.component('modalterceros-component', require('./components/terceros/ModalComponent.vue').default);
Vue.component('modaldeleteterceros-component', require('./components/terceros/ModalDeleteComponent.vue').default);
Vue.component('buscarterceros-component', require('./components/terceros/BuscarComponent.vue').default);
/**
 *Funcionalidades axios para las compras.
*/
Vue.component('tablacompras-component', require('./components/compras/TablaComponent.vue').default);
Vue.component('modalcompras-component', require('./components/compras/ModalComponent.vue').default);
Vue.component('modaldeletecompras-component', require('./components/compras/ModalDeleteComponent.vue').default);
Vue.component('buscarcompras-component', require('./components/compras/BuscarComponent.vue').default);
Vue.component('auxiliarterceros-component', require('./components/compras/TercerosAuxiliarComponent.vue').default);
Vue.component('addproductos-component', require('./components/compras/ProductosAddComponent.vue').default);
/**
 *Paginación general.
*/
Vue.component('pagination', require('laravel-vue-pagination'));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

