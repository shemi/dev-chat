<template>

    <div class="conversation-bar hero is-bold is-primary">
        <nav class="level">
            <!-- Left side -->
            <div class="level-left">
                <div class="level-item">
                    <div class="image is-32x32 conversation-image">
                        <img :src="conversation.image" :alt="conversation.name">
                    </div>
                </div>
                <div class="level-item">
                    <div class="subtitle is-6">
                        {{ conversation.name }}
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

    export default {

        props: [
            'conversation'
        ]

    }

</script>