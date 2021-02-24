import VueRouter from 'vue-router'
import Login from './../components/auth/Login.vue'
import Register from './../components/auth/Register.vue'
import IndexMain from './../components/pages/IndexMain.vue'
import Dashboard from './../components/pages/Dashboard.vue'
import UserImage from './../components/pages/UserImage.vue'

const routes = [
    { 
        path: '/', 
        name: 'IndexMain', 
        component: IndexMain, 
        meta: { requiresAuth: true }, 
        children: [
            { 
                path: '/dashboard', name: 'dashboard', 
                components: { default: IndexMain, MainView: Dashboard},  
            }, 
            { 
                path: '/image', name: 'image',  
                components: { default: IndexMain, MainView: UserImage},  
            }
        ]
    }, 
    { path: '/login', name: 'login', component: Login }, 
    { path: '/register', name: 'register', component: Register },  
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

function loggedIn(){
    return localStorage.getItem('token');
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
        if (!loggedIn()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});
export default router;