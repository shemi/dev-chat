import Vue from 'vue';
import Message from './Message';
import store from './store/index';
import {conversation as Resource} from './Resources/index';
import moment from 'moment';
import {isNull} from './Utils';
import _ from 'lodash';

class Conversation {

    constructor(attributes) {
        this._messagesLoaded = false;
        this._hasOlderMessage = false;
        this._fetchingMessages = false;
        this._currentMessagesPage = 0;
        this._messageCount = 0;
        this._connected = false;
        this._connection = null;
        this._colors = {};
        this._lastScrollTop = null;
        this._lastElClientHight = null;
        this._lastElScrollHeight = null;
        this._newMessagePlayer = new Audio(require('../sound/message.mp3'));

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

        if (_.isArray(attributes.messages) && attributes.messages.length > 0) {
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

    resetScrollPosition() {
        this._lastScrollTop = null;
        this._lastElClientHight = null;
        this._lastElScrollHeight = null;
    }

    setScrollPosition(el) {
        if(! el) {
            return this;
        }

        this._lastScrollTop = el.scrollTop;
        this._lastElClientHight = el.clientHeight;
        this._lastElScrollHeight = el.scrollHeight;

        return this;
    }

    getScrollPosition(el) {
        if (isNull([this._lastScrollTop, this._lastElClientHight, this._lastElScrollHeight])) {
            return null;
        }

        return el.scrollTop + (el.scrollHeight - this._lastElScrollHeight);
    }

    connect() {
        this._connection = Echo.private('conversation.' + this.conversationId);

        this._connection.listen('MessageSent', e => {
            this._newMessagePlayer.play();
            this.addMessage(e.message);
        });
    }

    isFetchingMessages() {
        return this._fetchingMessages;
    }

    fetchMessages() {
        return new Promise((resolve, reject) => {
            this._fetchingMessages = true;

            Resource.fetch(this.conversationId, {
                params: {
                    page: ++this._currentMessagesPage,
                    offset: this._messageCount
                }
            })
                .then(({data}) => {
                    this.addMessages(data.messages, true);
                    this._hasOlderMessage = data.hasMore;
                    this._fetchingMessages = false;
                    this._messagesLoaded = true;

                    resolve(this);
                })
                .catch(err => {
                    this._fetchingMessages = false;

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
        for(let message of messages) {
            this.addMessage(message, older);
        }
    }

    addMessage(message, older = false) {
        if (!(message instanceof Message)) {
            message = new Message(message, this);
        }

        let messages;
        let groupKey = message.createdAt.format('YYYY-MM-DD'),
            messagesObject;


        for(let group of this.messages) {
            if(group.date === groupKey) {
                messagesObject = group;
            }
        }

        console.log(message);

        if(! messagesObject) {
            messagesObject = {
                date: groupKey,
                messages: []
            };

            if (older) {
                this.messages.unshift(messagesObject);
            } else {
                this.messages.push(messagesObject);

            }
        }

        if (older) {
            messagesObject.messages.unshift(message);
        } else {
            messagesObject.messages.push(message);
        }

        this._messageCount++;

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

    getContactColor(id) {
        if (!id) {
            throw new Error('ID is needed');
        }

        if (this._colors[id]) {
            return this._colors[id];
        }

        let color = null,
            contact;

        for (contact of this.contacts) {
            if (contact.id === id) {
                this._colors[id] = color = contact.color;

                break;
            }
        }

        return color;
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