import Vue from 'vue';
import Message from './Message';
import store from './store/index';
import { conversation as Resource } from './Resources/index';
import moment from 'moment';

class Conversation {

    constructor(attributes) {
        this._messagesLoaded = false;
        this._hasOlderMessage = false;
        this._currentMessagesPage = 0;
        this._connected = false;

        if (attributes) {
            this.setAttributes(attributes);
        }
    }

    setAttributes(attributes) {
        this.setId(attributes.conversationId);
        this.name = attributes.name || '';
        this.lastMessage = attributes.lastMessage || '';
        this.lastMessageAt = attributes.lastMessageAt ? moment(attributes.lastMessageAt) : null;
        this.contacts = attributes.contacts || [];
        this.messages = [];

        if(_.isArray(attributes.messages) && attributes.messages.length > 0) {
            this.addMessages(attributes.messages);
        }

        this.image = attributes.image || '';
        this.isGroup = attributes.isGroup || false;
    }

    setId(id) {
        if (!id) {
            this.conversationId = null;

            return this;
        }

        this.conversationId = id;
        this.connect();

        return this;
    }

    connect() {
        console.log('connecting');
    }

    fetchMessages() {
        return new Promise((resolve, reject) => {
            let revertOnFail = ! this._messagesLoaded;
            this._messagesLoaded = true;

            Resource.fetch(this.conversationId, {
                params: {
                    page: ++this._currentMessagesPage,
                    offset: this.messages.length
                }
            })
            .then(({ data }) => {
                this.addMessages(data.messages, true);
                this._hasOlderMessage = data.hasMore;

                resolve(this);
            })
            .catch(err => {
                this._messagesLoaded = !revertOnFail;

                console.log(err);
                reject(err);
            });
        });
    }

    isMessagesLoaded() {
        return this._messagesLoaded;
    }

    hasOlderMessages() {
        return this._hasOlderMessage;
    }

    create() {
        return new Promise((resolve, reject) => {
            Resource.create(this.toJson())
                .then(({data}) => {
                    this.setAttributes(data);

                    Vue.nextTick(function () {
                        resolve(this);
                    });
                })
                .catch((err) => {
                    reject(err);
                });
        });
    }

    addMessages(messages, older = false) {
        _.each(messages, message => {
            this.addMessage(message, older);
        });
    }

    addMessage(message, older = false) {
        if (!(message instanceof Message)) {
            message = new Message(message, this);
        }

        if(older) {
            this.messages.unshift(message);
        } else {
            this.messages.push(message);
        }

        return this;
    }

    send(message, type = null) {
        if (!(message instanceof Message)) {
            message = Message.new(
                message,
                store.state.user,
                type || Message.TYPE_TEXT,
                this
            );

            this.addMessage(message);
        }

        if (!this.conversationId) {
            this.create()
                .then(res => {
                    this.send(message);
                });

            return this;
        }

        message.send();

        return this;
    }

    toJson() {
        return {
            'id': this.conversationId,
            'name': this.name,
            'users': this.contacts,
            'image': this.image,
            'group': this.isGroup
        }
    }

    static create(attributes) {
        return new Conversation(attributes);
    }

    static createFromUser(user) {
        return Conversation.create({
            'conversationId': null,
            'name': user.name,
            'lastMessage': '',
            'lastMessageAt': moment(),
            'contacts': [user],
            'messages': [],
            'image': user.image,
            'isGroup': false
        });
    }
}

export default Conversation;