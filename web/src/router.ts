import { createRouter, createWebHashHistory } from 'vue-router'

const routes = [
  {
    name: 'notFound',
    path: '/:path(.*)+',
    redirect: {
      name: 'notify'
    }
  },
  {
    name: 'pay',
    path: '/pay/:gid?',
    component: () => import('@/view/goods/index.vue'),
    meta: {
      title: 'pay'
    }
  },
  {
    name: 'notify',
    path: '/notify/:type?/:gid?',
    component: () => import('@/view/goods/success.vue'),
    meta: {
      title: 'notify'
    }
  }
]

const router = createRouter({
  routes,
  history: createWebHashHistory()
})

router.beforeEach((to, from, next) => {
  const title = to?.meta?.title
  if (title) {
    document.title = title as string
  }
  next()
})

export default router
