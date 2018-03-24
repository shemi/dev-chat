<template>

    <div class="media conversation-list-item"
         @click="select(conversation)"
         :class="{'is-active': isActive}">
        <figure class="media-left">
            <p class="image is-48x48">
                <img :src="conversation.image">
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <strong>{{ conversation.name }}</strong>
                    <br>
                    <small v-if="! conversation.isGroup">
                        @{{ conversation.contacts[0].username }}
                    </small>
                </p>
            </div>
        </div>
    </div>

</template>

<script>

    import { mapState, mapGetters, mapMutations } from 'vuex';

    export default {

        props: [
            'conversation',
            'isActive',
            'index'
        ],

        data() {
            return {

            }
        },

        methods: {
            ...mapMutations({
                select: 'selectConversation'
            })
        }

    }

</script>