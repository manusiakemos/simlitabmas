import Vue from 'vue'
import VueRouter from 'vue-router'
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
    return originalPush.call(this, location).catch(err => err)
}
import store from './store'

Vue.use(VueRouter);
// Containers
import DefaultContainer from "./containers/DefaultContainer"

// Views - Notifications
import Dashboard from "./views/dashboards/Index";
import User from "./views/user/Index";
import Anggota from "./views/anggota/Index";
import Penelitian from "./views/penelitian/Index";
import Pengabdian from "./views/pengabdian/Index";
import PenelitianAnggota from "./views/penelitian-anggota/Index";
import Profile from "./views/profile/Index";
import Setting from "./views/setting/Index";

// Views - Pages
import Page404 from './views/pages/Page404'
import Page500 from './views/pages/Page500'
import Login from './views/pages/Login'
// import Register from './views/pages/Register'

const routes = [
    {
        path: '/',
        redirect: '/dashboard',
        name: 'Home',
        component: DefaultContainer,
        children: [
            {
                path: 'dashboard',
                name: 'Dashboard',
                component: Dashboard,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: 'user',
                name: 'User',
                component: User,
                meta: {
                    requiresAuth: true,
                    role:'super-admin'
                }
            },
            {
                path: 'anggota',
                name: 'Anggota',
                component: Anggota,
                meta: {
                    requiresAuth: true,
                    role:'user'
                }
            },
            {
                path: 'penelitian',
                name: 'Penelitian',
                component: Penelitian,
                meta: {
                    requiresAuth: true,
                    role:'user|admin'
                }
            },
            {
                path: 'penelitian/anggota/:penelitian_id',
                name: 'PenelitianAnggota',
                component: PenelitianAnggota,
                meta: {
                    requiresAuth: true,
                    role:'user'
                }
            },
            {
                path: 'pengabdian',
                name: 'Pengabdian',
                component: Pengabdian,
                meta: {
                    requiresAuth: true,
                    role:'user|admin'
                }
            },
            {
                path: 'profile',
                name: 'Profile',
                component: Profile,
                meta: {
                    requiresAuth: true,
                    // role:'admin|super-admin'
                }
            },
            {
                path: 'setting',
                name: 'setting',
                component: Setting,
                meta: {
                    requiresAuth: true,
                    role:'admin|super-admin'
                }
            },
        ]
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
                component: Login,
                meta:{
                    redirectIfAuth:true,
                }
            },
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
            next({path: '/pages/login',})
        } else {
            let user = store.state.auth.data;
             $.ajaxSetup({
                 headers: {"Authorization": `Bearer ${user.api_token}`}
             });
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
                    next({path: '/pages/404'});
                }
            }
            next();
        }
    }
    else if (to.matched.some(record => record.meta.redirectIfAuth)) {
        if (store.state.auth.status) {
            next({
                path: '/dashboard',
                // params: { nextUrl: to.fullPath }
            })
        } else {
            next()
        }

    } else {
        next();
    }
});

export default router;

