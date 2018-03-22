<template>

    <div class="media" :class="{'is-active': isActive}">
        <figure class="media-left">
            <p class="image is-32x32">
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

    export default {

        props: [
            'conversation',
            'isActive'
        ],

        data() {
            return {

            }
        }

    }

</script>