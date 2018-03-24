<template>

    <div class="main-app">
        <section class="main-app-section">
            <div class="container">
                <div class="section">
                    <div class="chat-board">

                        <div class="chat-sidebar">
                            <sidebar></sidebar>
                        </div>

                        <div class="chat-room">
                            <conversation v-if="conversation"></conversation>
                            <div class="has-text-centered is-size-2 has-text-success" v-else>
                                Select conversation
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>

</template>

<script>
    import Conversation from './Conversation';
    import Sidebar from './Sidebar';
    import { mapState, mapGetters, mapActions } from 'vuex';
    import { base as resource } from '../Resources/index';

    export default {

        data() {
            return {
                loadingInst: this.$loading.open(),
            }
        },

        created() {
            this.fetchAppStatus();
        },

        methods: {
            fetchAppStatus() {
                resource.fetch()
                    .then(({ data }) => {
                        this.$store.commit('setUser', data.user);
                        this.$store.commit('setConversations', data.conversations);
                        this.loadingInst.close();
                    })
                    .catch();
            }
        },

        computed: {
            ...mapGetters({
                conversation: 'getSelectConversation'
            })
        },

        components: {
            Sidebar,
            Conversation
        }

    }

</script>