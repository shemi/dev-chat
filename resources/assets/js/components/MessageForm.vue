<template>

    <div class="message-form-container">
        <div class="message-form">
            <div class="level">
                <div class="level-item is-narrow">
                    <button class="button is-text is-thin"
                            :class="{'text-normal': isPikerOpen}"
                            type="button"
                            @click.prevent="togglePiker">
                        <b-icon :icon="isPikerOpen ? 'times' : 'smile'" pack="fas"></b-icon>
                    </button>
                </div>
                <div class="level-item message-form-item">
                    <div class="editable-input-holder">
                        <editable v-model="message"
                                  ref="textarea"
                                  @send="sendMessage"
                                  placeholder="Type a message"></editable>
                    </div>
                </div>
                <div class="level-item is-narrow">
                    <button class="button is-text text-normal" type="button" v-if="! message">
                        <b-icon icon="paperclip"></b-icon>
                    </button>
                    <button class="button is-text"
                            @click="sendMessage"
                            type="button"
                            v-else>
                        <b-icon icon="paper-plane"></b-icon>
                    </button>
                </div>
            </div>
        </div>

        <div class="emoji-piker" v-if="isPikerOpen">
            <picker set="google"
                    title="Pick your emoji…"
                    emoji="point_up"
                    :autoFocus="true"
                    color=""
                    @click="addEmoji"
                    :style="{width: '100%', borderRadius: 0}">
            </picker>
        </div>
    </div>

</template>

<script>

    import Editable from './Editable';
    import { Picker } from 'emoji-mart-vue';

    export default {

        props: [
            'conversation'
        ],

        data() {
            return {
                message: '',
                isPikerOpen: false,
            }
        },

        methods: {

            sendMessage(value) {
                this.$refs.textarea.update();
                this.conversation.send(this.message);
                this.$refs.textarea.clearInput();
            },

            togglePiker() {
                this.isPikerOpen = !this.isPikerOpen;
                this.$refs.textarea.focus(false);
            },

            addEmoji(emoji, event) {
                this.$refs.textarea.appendText(emoji.colons);
            }

        },

        components: {
            Editable,
            Picker
        }


    }

</script>