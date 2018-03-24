import Vue from 'vue';
import _ from 'lodash';
import Conversation from '../Conversation';

export default {
    state: {
        conversations: [],
        selectedConversation: null
    },

    mutations: {
        setConversations(state, conversations) {
            _.each(conversations, function(conversation) {
                state.conversations.push(new Conversation(conversation));
            });
        },

        addConversation(state, conversation) {
            state.conversations.unshift(conversation);
        },

        selectConversation(state, conversation) {
            Vue.set(state, 'selectedConversation', conversation);
        },

        selectConversationById(state, id) {
            Vue.set(
                state,
                'selectedConversation',
                _.find(state.conversations, ['conversationId', id])
            );
        }
    },

    getters: {
        getConversations(state) {
            return _.reverse(_.sortBy(state.conversations, ['lastMessageAt']));
        },

        getSelectConversation(state) {
            return state.selectedConversation;
        },

        getSelectConversationId(state) {
            return state.selectedConversation ? state.selectedConversation.conversationId : '';
        }
    },

    actions: {
        createConversation({ commit, state }, user) {
            let conversation = Conversation.createFromUser(user);
            commit('addConversation', conversation);
            commit('selectConversation', conversation);
        }
    }
}