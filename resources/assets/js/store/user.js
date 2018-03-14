import Vue from 'vue';

export default {
    state: {

    },

    mutations: {
        setUser(state, user) {
            let keys = Object.keys(user);

            for (let i in keys) {
                Vue.set(state, keys[i], user[keys[i]]);
            }
        }
    },

    getters: {
        userImage: state => {
            if(! state.image) {
                return 'https://use.fontawesome.com/releases/v5.0.8/svgs/solid/user.svg';
            }

            return state.image;
        }
    },

    actions: {

    }
}