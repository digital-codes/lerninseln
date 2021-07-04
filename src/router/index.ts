import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import PickUp from '../views/PickUp.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/intro'
  },
  {
    path: '/',
    component: PickUp,
    children: [
      {
        path: '',
        redirect: '/intro'
      },
      {
        path: 'intro',
        component: () => 
          import('@/views/Intro.vue')
        
      },
      {
        path: 'map',
        component: () => import('@/views/Map.vue')
      },
      {
        path: 'tickets',
        component: () => import('@/views/Tickets.vue')
      },
      {
        path: 'codes',
        component: () => import('@/views/Codes.vue')
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
