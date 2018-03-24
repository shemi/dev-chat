<template>

    <div class="conversations-list">

        <conversations-list-item v-for="(conversation, index) in conversations"
                                 :conversation="conversation"
                                 :index="index"
                                 :is-active="conversation === selectedConversation"
                                 :key="conversation.conversationId">
        </conversations-list-item>

    </div>

</template>

<script>
    import { mapState, mapGetters } from 'vuex';
    import ConversationsListItem from './ConversationsListItem';

    export default {

        data() {
            return {

            }
        },

        computed: {
            // ...mapState({
            //     user: 'user',
            // }),
            ...mapGetters({
                selectedConversation: 'getSelectConversation',
                selectConversationId: 'getSelectConversationId',
                conversations: 'getConversations'
            })
        },

        components: {
            ConversationsListItem
        }

    }

</script>