import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Home')
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

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});