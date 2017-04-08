import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Home')
    },
    {
        path: '/login',
        component: require('./views/Login')
    },
    {
        path: '/register',
        component: require('./views/Register')
    },
    {
        path: '/:channel',
        component: require('./views/Login')
    },
];

export default new VueRouter({
    routes
});