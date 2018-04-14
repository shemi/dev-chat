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
                    <small v-if="! conversation.isGroup">
                        <br>
                        @{{ conversation.getDirectContact().username }}
                    </small>
                </p>
            </div>
        </div>
        <div class="media-right">
            <b-tag type="is-primary" v-if="conversation.newMessageCount">
                {{ conversation.newMessageCount }}
            </b-tag>
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