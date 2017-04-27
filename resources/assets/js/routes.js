import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Home')
    },
    {
        path: '/reset-password/:token',
        component: require('./views/ResetPassword')
    },
    {
        path: '/threads',
        component: require('./views/Home')
    },
    {
        path: '/new-thread',
        component: require('./views/NewThread'),
    },
    {
        path: '/sign-in',
        component: require('./views/SignIn')
    },
    {
        path: '/@:username',
        component: require('./views/Profile')
    },
    {
        path: '/:channel',
        component: require('./views/Threads'),
        name: 'channel'
    },
    {
        path: '/:channel/:thread',
        component: require('./views/Thread')
    }
    
    
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});

router.beforeEach((to, from, next) => {
    VueScrollTo.scrollTo('.column.is-9');    
    next();
    
})
export default router;