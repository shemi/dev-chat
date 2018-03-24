<template>

    <div class="editable-area" @click="saveSelection">
        <div class="editable-placeholder" v-show="! newValue">
            {{ placeholder }}
        </div>

        <div class="editable-input"
             @input="update"
             dir="auto"
             @keypress.enter="onEnter"
             @keyup="onKeyPress"
             ref="textarea">
        </div>
    </div>

</template>

<script>
    import EmojiConvertor from '../Services/EmojiConvertor';
    import TurndownService from 'turndown';

    const defaultParagraphSeparatorString = 'defaultParagraphSeparator';
    const formatBlock = 'formatBlock';
    const queryCommandValue = command => document.queryCommandValue(command);
    const exec = (command, value = null) => document.execCommand(command, true, value);

    export default {

        props: ['value', 'placeholder'],

        data() {
            return {
                newValue: this.value,
                selection: null,
            }
        },

        watch: {

        },

        mounted() {
            const defaultParagraphSeparator = 'div';
            const content =  this.$refs.textarea;
            content.contentEditable = true;

            exec("insertBrOnReturn", "true");
            this.focus();
        },

        methods: {

            update(event) {
                this.newValue = this.sanitizeValue(this.$refs.textarea.innerHTML);
                this.$emit('input', this.newValue);
                this.saveSelection();
            },

            sanitizeValue(value) {
                const emojiRegex = /<img.*?title=["|'](.*?)["|'].*?>/gm;
                value = value.replace(emojiRegex, ':$1:');

                return (new TurndownService({
                    headingStyle: 'atx',
                    fence: '```',
                    codeBlockStyle: 'fenced'
                })).turndown(value);
            },

            onKeyPress(e) {
                let keys = [
                    37, 39,
                    38, 40,
                    35, 36,
                    34, 33,
                    65
                ];

                if(keys.indexOf(e.keyCode) >= 0) {
                    this.saveSelection();
                }
            },

            saveSelection() {
                if(this.selection) {
                    window.rangy.removeMarkers(this.selection);
                    this.selection = null;
                }

                if(! this.newValue) {

                    return;
                }

                this.selection = window.rangy.saveSelection();
            },

            focus(restoreSelection = true) {
                if(this.selection && restoreSelection) {
                    window.rangy.restoreSelection(this.selection);
                }

                this.$refs.textarea.focus();
            },

            convertEmojies() {
                this.$refs.textarea.innerHTML = EmojiConvertor.replace_colons(this.$refs.textarea.innerHTML);
            },

            clearInput() {
                if(this.selection) {
                    window.rangy.removeMarkers(this.selection);
                    this.selection = null;
                }

                this.newValue = "";
                this.$refs.textarea.innerHTML = "";
                this.$emit('input', '');
            },

            appendText(text) {
                this.focus();
                exec('insertHTML', EmojiConvertor.replace_colons(text));
            },

            onEnter(event) {
                if (event.shiftKey) {
                    return;
                }

                event.preventDefault();
                this.$emit('send', this.newValue);
            },
        }

    }

</script>