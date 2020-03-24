import Vue from 'vue';
import Router from 'vue-router';
import axios from "axios";
Vue.use(Router);
const router =new Router({
    mode: 'history',
    base: "/",
    routes: [
        {
            path: '',
            component: () => import('./src/plantilla/Menu.vue'),
            children: [
                {
                    path: '/',
                    name: 'inicio',
                    component: () => import('./src/componentes/Usuarios.vue')
                },
                {
                    path: '/persona',
                    name: 'persona',
                    component: () => import('./src/componentes/Persona.vue')
                },
            ]
        }, 
        {
            path: "*",
            component: () => import('./src/componentes/404.vue')
        }
    ],
});
//ejemplo
export default router;
