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

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>

</template>

<script>

    import Sidebar from './Sidebar';

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
                window.axios.get(window.App.api_base)
                    .then(({ data }) => {
                        this.$store.commit('setUser', data.data.user);
                        this.loadingInst.close();
                    })
                    .catch();
            }
        },

        components: {
            Sidebar
        }

    }

</script>