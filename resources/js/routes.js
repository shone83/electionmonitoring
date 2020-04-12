import Admin from './components/Admin.vue';
import Users from './components/Users.vue';
import Tokens from './components/Tokens.vue';
import Roles from './components/Roles.vue';
import Districts from './components/Districts.vue';
import Towns from './components/Towns.vue';

export default [
    { path: '/admin', component: Admin},
    { path: '/users', component: Users},
    { path: '/tokens', component: Tokens},
    { path: '/roles', component: Roles},
    { path: '/districts', component: Districts},
    { path: '/towns', component: Towns}
]