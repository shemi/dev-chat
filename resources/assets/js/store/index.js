import Vue from 'vue';
import Vuex from 'vuex';
import user from './user';
import conversations from './conversations';

Vue.use(Vuex);

export default new Vuex.Store({
    strict: false, //process.env.NODE_ENV !== 'production',

    modules: {
        user,
        conversations
    }
})