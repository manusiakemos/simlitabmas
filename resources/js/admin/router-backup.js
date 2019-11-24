import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter);
// Containers
import DefaultContainer from "./containers/DefaultContainer"

// Views - Notifications
import Alerts from './views/notifications/Alerts'
import Badges from './views/notifications/Badges'
import Modals from './views/notifications/Modals'

// Views - Pages
import Page404 from './views/pages/Page404'
import Page500 from './views/pages/Page500'
import Login from './views/pages/Login'
import Register from './views/pages/Register'

const routes = [
    {
        path: '/',
        redirect: '/pages/login',
        name: 'Home',
        component: DefaultContainer,
        children: [
            {
                path: 'notifications',
                redirect: '/notifications/alerts',
                name: 'Notifications',
                component: {
                    render(c) {
                        return c('router-view')
                    }
                },
                children: [
                    {
                        path: 'alerts',
                        name: 'Alerts',
                        component: Alerts,
                        meta:{
                            requiresAuth:true
                        }
                    },
                    {
                        path: 'badges',
                        name: 'Badges',
                        component: Badges
                    },
                    {
                        path: 'modals',
                        name: 'Modals',
                        component: Modals
                    }
                ]
            }
        ]
    },
    {
        path: '*',
        name: 'Page404Main',
        component: Page404
    },
    {
        path: '/pages',
        redirect: '/pages/404',
        name: 'Pages',
        component: {
            render(c) {
                return c('router-view')
            }
        },
        children: [
            {
                path: '404',
                name: 'Page404',
                component: Page404
            },
            {
                path: '500',
                name: 'Page500',
                component: Page500
            },
            {
                path: 'login',
                name: 'Login',
                component: Login
            },
            {
                path: 'register',
                name: 'Register',
                component: Register
            }
        ]
    }
];


const router = new VueRouter({
    mode: 'hash', // https://router.vuejs.org/api/#mode
    linkActiveClass: 'open active',
    scrollBehavior: () => ({y: 0}),
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.state.auth.status == false) {
            next({
                path: '/',
                // params: { nextUrl: to.fullPath }
            })
        } else {
            /* if (screen.width < 1000 && !$("body").hasClass("sidebar-gone")) {
                 $(document).find("#sidebar-toggler").data("toggle", "sidebar").click();
             }*/
            let user = store.state.auth.data;
            /* $.ajaxSetup({
                 headers: {"Authorization": `Bearer ${user.api_token}`}
             });*/
            if (to.matched.some(record => record.meta.role)) {
                var userRole = user.role;
                var metaRole = to.meta.role;
                var splitMetaRole = metaRole.split('|');

                // console.log(userRole, metaRole, splitMetaRole);

                if (splitMetaRole.indexOf(userRole) > -1) {
                    next()
                    // console.log(userRole == metaRole);
                } else {
                    // console.log(userRole == metaRole)
                    next({path: '/'});
                }
            }
            next();
        }
    }
    else if (to.matched.some(record => record.meta.redirectIfAuth)) {
        if (store.state.auth.status) {
            next({
                path: '/home',
                // params: { nextUrl: to.fullPath }
            })
        }else{
            next()
        }

    }else {
        next();
    }
});

export default router;

