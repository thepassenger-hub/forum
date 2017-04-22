import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Homev2')
    },
    {
        path: '/threads',
        component: require('./views/Homev2')
    },
    {
        path: '/sign-in',
        component: require('./views/SignIn')
    },
    {
        path: '/profile',
        component: require('./views/Profile'),
    },
    {
        path: '/:channel',
        component: require('./views/Threads'),
        name: 'channel'
    },
    {
        path: '/:channel/new-thread',
        component: require('./views/NewThread'),
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