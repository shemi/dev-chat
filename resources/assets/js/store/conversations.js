import Vue from 'vue';
import _ from 'lodash';
import Conversation from '../Conversation';

export default {
    state: {
        conversations: [],
        selectedConversation: null
    },

    mutations: {
        addConversation(state, conversation) {
            state.conversations.unshift(conversation);
        },

        selectConversation(state, index) {
            Vue.set(state, 'selectedConversation', state.conversations[index]);
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
        }
    },

    actions: {
        createConversation({ commit, state }, user) {
            let conversation = Conversation.createFromUser(user);
            commit('addConversation', conversation);
            commit('selectConversation', 0);



        }
    }
}