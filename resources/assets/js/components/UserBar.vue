<template>

    <div class="user-bar">

        <nav class="level">
            <!-- Left side -->
            <div class="level-left">
                <div class="level-item">
                    <div class="image is-32x32">
                        <img :src="userImage" :alt="user.name">
                    </div>
                </div>
                <div class="level-item">
                    <div class="subtitle is-6">
                        @{{ user.username }}
                    </div>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <b-dropdown position="is-bottom-left">
                        <b-icon icon="chevron-down" slot="trigger"></b-icon>

                        <b-dropdown-item has-link>
                            <a href="https://google.com">
                                <b-icon icon="link"></b-icon>
                                Google (link)
                            </a>
                        </b-dropdown-item>
                        <b-dropdown-item value="home">
                            <b-icon icon="home"></b-icon>
                            Home
                        </b-dropdown-item>
                        <b-dropdown-item value="logout">
                            <b-icon icon="sign-out-alt"></b-icon>
                            Logout
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>
        </nav>
    </div>

</template>

<script>

    import { mapState, mapGetters } from 'vuex';

    export default {

        data() {
            return {

            }
        },

        computed: {
            ...mapState({
                user: 'user',
            }),
            ...mapGetters([
                'userImage'
            ])
        }

    }

</script>