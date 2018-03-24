import store from './store/index';
import { message as Resource } from './Resources/index';
import moment from 'moment';

class Message {

    static TYPE_TEXT = 1;
    static TYPE_IMAGE = 2;
    static TYPE_VIDEO = 3;
    static TYPE_DOCUMENT = 4;

    constructor(attributes, conversation) {
        this._conversation = conversation;

        if(attributes) {
            this.setAttributes(attributes);
        }
    }

    setAttributes(attributes) {
        this.by = attributes.by;
        this.createdAt = moment(attributes.createdAt);
        this.id = attributes.id;
        this.body = attributes.body || null;
        this.type = attributes.type || Message.TYPE_TEXT;
        this.sent = attributes.sent || false;
        this.read = attributes.read || false;
        this.mine = attributes.mine || attributes.by.id === store.state.user.id;
    }

    send() {
        if(this.id || ! this._conversation.conversationId) {
            return false;
        }

        Resource.send(this.toJson())
            .then(({ data }) => {
                this.setAttributes(data);
            })
            .catch(err => {
                console.log('message err');
            });
    }

    toJson() {
        return {
            'body': this.body,
            'id': this.id,
            'conversationId': this._conversation.conversationId,
            'type': this.type
        }
    }

    static new(message, user, type, conversation) {
        return new Message({
            'body': message.toString(),
            'by': user,
            'type': type,
            'createdAt': moment(),
            'id': null,
            'sent': false,
            'read': false,
            'mine': store.state.user.id === user.id
        }, conversation);
    }

}

export default Message;