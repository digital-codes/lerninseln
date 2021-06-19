import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import Tabs from '../views/PickUp.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/intro'
  },
  {
    path: '/',
    component: Tabs,
    children: [
      {
        path: '',
        redirect: '/intro'
      },
      {
        path: 'intro',
        component: () => import('@/views/Intro.vue')
      },
      {
        path: 'topics',
        component: () => import('@/views/Topics.vue')
      },
      {
        path: 'map',
        component: () => import('@/views/Map.vue')
      },
      {
        path: 'calendar',
        component: () => import('@/views/Dates.vue')
      },
      {
        path: 'tickets',
        component: () => import('@/views/Tickets.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
