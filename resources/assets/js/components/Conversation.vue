<template>

    <div class="conversation">

        <conversation-bar :conversation="conversation"></conversation-bar>

        <chat :messages="conversation.messages"
              :loading="loadingMessages"
              :conversation-id="lastConversationId"></chat>

        <message-form :conversation="conversation"></message-form>

    </div>

</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex';
    import ConversationBar  from './ConversationBar';
    import MessageForm  from './MessageForm';
    import Chat  from './Chat';

    export default {

        data() {
            return {
                loadingMessages: false,
                lastConversationId: null
            }
        },

        mounted() {
            this.initMessages();
        },

        watch: {
            conversation() {
                if(this.lastConversationId !== this.conversation.id) {
                    this.initMessages();
                }
            }
        },

        methods: {

            initMessages() {
                this.lastConversationId = this.conversation.conversationId;
                this.loadMessages();
            },

            loadMessages(scroll = false) {
                if(! scroll && this.conversation.isMessagesLoaded()) {
                    return;
                }

                if(scroll && this.conversation.hasOlderMessages()) {
                    return;
                }

                this.loadingMessages = true;

                this.conversation.fetchMessages()
                    .then(conversation => {
                        this.loadingMessages = false;
                    })
                    .catch(err => {
                        this.loadingMessages = false;
                    });
            }

        },

        computed: {
            ...mapGetters({
                conversation: 'getSelectConversation'
            })
        },

        components: {
            MessageForm,
            ConversationBar,
            Chat
        }

    }

</script>