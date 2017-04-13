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
        path: '/:channel',
        component: require('./views/Threads')
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