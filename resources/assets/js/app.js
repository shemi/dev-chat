import './bootstrap';
import Vue from 'vue'
import Buefy from 'buefy';
import App from './components/App';
import store from './store/index';
import EmojiConvertor from 'emoji-js';

Vue.use(Buefy, {
    defaultIconPack: 'fas',
});

const app = new Vue({
    el: '#app',
    store,
    components: {
        App
    }

});
