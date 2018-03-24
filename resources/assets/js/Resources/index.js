import axios from 'axios';
import BaseResource from './BaseResource';
import ConversationResource from './ConversationResource';
import MessageResource from './MessageResource';

const http = axios.create({
    baseURL: window.App.api_base
});

const base = new BaseResource({ http });
const conversation = new ConversationResource({ http });
const message = new MessageResource({ http });

export {
    base,
    conversation,
    message
}