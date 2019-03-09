export default [
    {
        name: '/preview',
        path: '/preview',
        meta: {
            title: '截图'
        },
        component: () => import('./preview.vue')
    },
    {
        name: '/support',
        path: '/support',
        meta: {
            title: '支持'
        },
        component: () => import('./support.vue')
    }
]
