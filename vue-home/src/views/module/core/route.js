export default [
    {
        name: '/user/login',
        path: '/user/login',
        meta: {
            title: '用户登录'
        },
        component: () => import('./user/login.vue')
    }
]
