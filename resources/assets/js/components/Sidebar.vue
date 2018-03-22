<template>

    <div class="conversations-sidebar">
        <user-bar></user-bar>

        <chat-search></chat-search>

        <div class="messages">

        </div>

        <conversations-list></conversations-list>

    </div>

</template>

<script>

    import UserBar from './UserBar';
    import ChatSearch from './ChatSearch';
    import ConversationsList from './ConversationsList';

    export default {

        data() {
            return {

            }
        },

        components: {
            UserBar,
            ChatSearch,
            ConversationsList
        }

    }

</script>