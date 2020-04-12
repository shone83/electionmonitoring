import Admin from './components/Admin.vue';
import Users from './components/Users.vue';
import Tokens from './components/Tokens.vue';
import Roles from './components/Roles.vue';
import Districts from './components/Districts.vue';

export default [
    { path: '/admin', component: Admin},
    { path: '/users', component: Users},
    { path: '/tokens', component: Tokens},
    { path: '/roles', component: Roles},
    { path: '/districts', component: Districts}
]