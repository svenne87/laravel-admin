window.Vue = require('vue');

export default [
    { 
        path: '/', 
        name: 'home', 
        component: require('./components/admin/dashboard/Dashboard.vue') 
    },
    { 
        path: '/users', 
        name: 'users', 
        component: require('./components/admin/users/UsersTable.vue'), 
        meta: {requiresPermissions: ['show users']},
    },
    { 
        path: '/users/:id', 
        name: 'user-edit', 
        component: require('./components/admin/users/UserForm.vue'),
        meta: {requiresPermissions: ['edit users']},
    },
    { 
        path: '/users/create', 
        name: 'user-create', 
        component: require('./components/admin/users/UserForm.vue'),
        meta: {requiresPermissions: ['create users']}, 
    },
    { 
        path: '/roles', 
        name: 'roles', 
        component: require('./components/admin/roles/RolesTable.vue'),
        meta: {requiresPermissions: ['show roles']},
    },
    { 
        path: '/roles/:id', 
        name: 'role-edit', 
        component: require('./components/admin/roles/RoleForm.vue'),
        meta: {requiresPermissions: ['edit roles']},
    },
    { 
        path: '/roles/create', 
        name: 'role-create', 
        component: require('./components/admin/roles/RoleForm.vue'),
        meta: {requiresPermissions: ['create roles']},
    },
    { 
        path: '/pages', 
        name: 'pages', 
        component: require('./components/admin/pages/PagesTable.vue'),
        meta: {requiresPermissions: ['show posts']},
    },
    { 
        path: '/pages/:id', 
        name: 'page-edit', 
        component: require('./components/admin/pages/PageForm.vue'),
        meta: {requiresPermissions: ['edit posts']},
    },
    { 
        path: '/pages/create',
        name: 'page-create', 
        component: require('./components/admin/pages/PageForm.vue'),
        meta: {requiresPermissions: ['create posts']},
    },
]