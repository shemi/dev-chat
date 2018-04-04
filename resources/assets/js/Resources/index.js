import axios from 'axios';
import BaseResource from './BaseResource';
import ConversationResource from './ConversationResource';
import MessageResource from './MessageResource';

const http = axios.create({
    baseURL: window.App.api_base
});

http.interceptors.request.use(function (config) {
    if (window.Echo.socketId()) {
        config.headers['X-Socket-Id'] = window.Echo.socketId();
    }

    return config;
});

const base = new BaseResource({ http });
const conversation = new ConversationResource({ http });
const message = new MessageResource({ http });

export {
    base,
    conversation,
    message
}