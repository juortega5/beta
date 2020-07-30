import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
	routes: [
		{
			path:'/',
			name:'home',
			component: require('./components/layouts/AppComponent').default
		},
		{
			path:'/productos',
			name:'productos',
			component: require('./views/productos/ProductosComponent').default
		},
		{
			path:'/terceros',
			name:'terceros',
			component: require('./views/terceros/TercerosComponent').default
		},
		{
			path:'/compras',
			name:'compras',
			component: require('./views/compras/ComprasComponent').default
		},
	],
	mode: 'history'
})