import Vue from 'vue';
import Message from './Message';
import store from './store/index';
import {conversation as Resource, message as MessageResource} from './Resources/index';
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

        this._directContact = null;

        this._messagesIdsToUpdateStatus = [];
        this._updatingMessagesStatus = false;

        this._newMessageAudioPlayer = new Audio(require('../sound/message.mp3'));

        if (attributes) {
            this.setAttributes(attributes);
        }
    }

    setAttributes(attributes) {
        this.setId(attributes.conversationId);
        this.name = attributes.name || '';
        this.lastMessage = attributes.lastMessage || '';
        this.lastMessageAt = attributes.lastMessageAt ? moment(attributes.lastMessageAt) : null;
        this.contacts = _.keyBy(attributes.contacts, 'id') || {};
        this.messages = [];
        this.newMessageCount = attributes.newMessageCount || 0;

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
        this._connected = true;

        this._connection.listen('MessageSent', e => {
            this._newMessageAudioPlayer.play();
            this.newMessageCount++;
            e.message.isNew = true;
            this.addMessage(e.message);
        });
    }

    isConnected() {
        return this._connected;
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

        let groupKey = message.createdAt.format('YYYY-MM-DD'),
            messagesObject;


        for(let group of this.messages) {
            if(group.date === groupKey) {
                messagesObject = group;
            }
        }

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
            this.lastMessage = message;
            this.lastMessageAt = message.createdAt;
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

        }

        this.addMessage(message);

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

    updateMessageStatus(id) {
        this._messagesIdsToUpdateStatus.push(id);

        _.debounce(this.updateMessagesStatus.bind(this), 600)();
    }

    updateMessagesStatus() {
        if(this._messagesIdsToUpdateStatus.length <= 0) {
            return;
        }

        if(this._updatingMessagesStatus) {
            setTimeout(function() {
                this.updateMessagesStatus();
            }.bind(this), 0);

            return;
        }

        let ids = _.clone(this._messagesIdsToUpdateStatus);
        this._messagesIdsToUpdateStatus.length = 0;
        this._updatingMessagesStatus = 1;

        MessageResource.updateStatuses(_.uniq(ids), this.conversationId)
            .then(({data}) => {
                this._updatingMessagesStatus = false;

                _.each(data.messages, function(message) {
                    if(this.newMessageCount > 0) {
                        this.newMessageCount--;
                    }
                }.bind(this));
            })
            .catch(err => {
                this._updatingMessagesStatus = false;
            });
    }

    getContact(id) {
        return this.contacts[id] || {};
    }

    getDirectContact() {
        if(this.isGroup) {
            return false;
        }

        if(this._directContact) {
            return this._directContact;
        }

        this._directContact = this.getContact(_.findKey(this.contacts, function(contact) {
            return contact.id !== store.state.user.id;
        }));

        return this._directContact;
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