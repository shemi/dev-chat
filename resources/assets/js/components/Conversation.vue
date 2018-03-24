<template>

    <div class="conversation">

        <conversation-bar :conversation="conversation"></conversation-bar>

        <div class="chat">

            <div class="message-row" v-for="message in conversation.messages">

                <div class="chat-message">

                    <div class="sender-avatar">
                        <div class="image is-48x48">
                            <img :src="message.by.image" :alt="message.by.name">
                        </div>
                    </div>

                    <div class="body">
                        <div class="sender-name">
                            {{ message.by.name }} <small>@{{ message.by.username }}</small>
                        </div>
                        <div class="content" v-html="message.body"></div>
                        <div class="time">
                            {{ message.createdAt.format('hh:mm A') }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <message-form :conversation="conversation"></message-form>

    </div>

</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex';
    import ConversationBar  from './ConversationBar';
    import MessageForm  from './MessageForm';

    export default {

        data() {
            return {

            }
        },

        methods: {



        },

        computed: {
            ...mapGetters({
                conversation: 'getSelectConversation'
            })
        },

        components: {
            MessageForm,
            ConversationBar
        }

    }

</script>