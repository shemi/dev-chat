<template>

    <div class="conversations-sidebar">
        <user-bar></user-bar>

        <chat-search></chat-search>

        <div class="messages">

        </div>

        <div class="conversation">

        </div>

    </div>

</template>

<script>

    import UserBar from './UserBar';
    import ChatSearch from './ChatSearch';

    export default {

        data() {
            return {

            }
        },

        components: {
            UserBar,
            ChatSearch
        }

    }

</script>