import Admin from './components/Admin.vue';
import District from './components/District.vue';
import Users from './components/Users.vue';
import Tokens from './components/Tokens.vue';
import Roles from './components/Roles.vue';

export default [
    { path: '/admin', component: Admin},
    { path: '/district', component: District},
    { path: '/users', component: Users},
    { path: '/tokens', component: Tokens},
    { path: '/roles', component: Roles}
]