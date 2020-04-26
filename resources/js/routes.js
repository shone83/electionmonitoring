import Dashboard from './components/Dashboard.vue';
import Users from './components/Users.vue';
import Tokens from './components/Tokens.vue';
import Roles from './components/Roles.vue';
import Districts from './components/Districts.vue';
import Towns from './components/Towns.vue';
import Settlements from './components/Settlements.vue';
import ElectoralUnits from './components/ElectoralUnits.vue';
import Statistic from './components/Statistic.vue';
import LocalList from './components/LocalList.vue';
import RepublicList from './components/RepublicList.vue';
import PresidentialList from './components/PresidentialList.vue';
import Turnout from './components/Turnout.vue';
import LocalResult from './components/LocalResult.vue';
import RepublicResult from './components/RepublicResult.vue';
import PresidentialResult from './components/PresidentialResult.vue';

export default [
    { path: '/dashboard', component: Dashboard},
    { path: '/users', component: Users},
    { path: '/tokens', component: Tokens},
    { path: '/roles', component: Roles},
    { path: '/districts', component: Districts},
    { path: '/towns', component: Towns},
    { path: '/settlements', component: Settlements},
    { path: '/electoral_units', component: ElectoralUnits},
    { path: '/statistic', component: Statistic},
    { path: '/local_list', component: LocalList},
    { path: '/republic_list', component: RepublicList},
    { path: '/presidential_list', component: PresidentialList},
    { path: '/turnout', component: Turnout},
    { path: '/local_result', component: LocalResult},
    { path: '/republic_result', component: RepublicResult},
    { path: '/presidential_result', component: PresidentialResult}
]