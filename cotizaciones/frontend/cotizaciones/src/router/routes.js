const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('pages/IndexPage.vue')
      },
      {
        path: 'clients',
        name: 'clients',
        component: () => import('pages/ClientsPage.vue')
      },
      {
        path: 'projects',
        name: 'projects',
        component: () => import('pages/ProjectsPage.vue')
      },
      {
        path: 'services',
        name: 'services',
        component: () => import('pages/ServicesPage.vue')
      },
      {
        path: 'materials',
        name: 'materials',
        component: () => import('pages/MaterialsPage.vue')
      },
      {
        path: 'quotes',
        name: 'quotes',
        component: () => import('pages/QuotesPage.vue')
      },
      {
        path: 'quotes/create',
        name: 'quote-create',
        component: () => import('pages/QuoteForm.vue')
      },
      {
        path: 'quotes/:id/edit',
        name: 'quote-edit',
        component: () => import('pages/QuoteForm.vue')
      }
    ]
  }
 ]

export default routes