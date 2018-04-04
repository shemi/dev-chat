<template>

    <div class="chat-message">

        <div class="sender-avatar">
            <div class="image is-48x48" :style="avatarStyle">
                <img :src="message.by.image" :alt="message.by.name">
            </div>
        </div>

        <div class="body" :style="bodyStyle">
            <div class="sender-name">
                {{ message.by.name }}
                <small>@{{ message.by.username }}</small>
            </div>
            <div class="content" v-html="body"></div>
            <div class="time">
                {{ message.createdAt.format('hh:mm A') }}
            </div>
        </div>

    </div>

</template>

<script>
    import EmojiConvertor from '../Services/EmojiConvertor';
    import showdown from '../Services/Showdown';

    export default {

        props: [
            'message'
        ],

        data() {
            return {

            }
        },

        mounted() {

        },

        computed: {
            body() {
                return EmojiConvertor.replace_colons(
                    showdown.convert(this.message.body)
                );
            },

            avatarStyle() {
                if(! this.message.by || ! this.message.by.color) {
                    return {};
                }

                return {
                    borderColor: this.message.by.color
                }
            },

            bodyStyle() {
                if(! this.message.by || ! this.message.by.color) {
                    return {};
                }

                return {
                    color: this.message.by.color,
                    backgroundColor: this.message.by.color
                }
            }

        }

    }

</script>