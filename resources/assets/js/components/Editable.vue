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
             @paste="onPaste"
             ref="textarea">
        </div>
    </div>

</template>

<script>
    import EmojiConvertor from '../Services/EmojiConvertor';
    import striptags from 'striptags';

    const formatBlock = 'formatBlock';
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
                const emojiRegex = /<img.*?title=["|'](.*?)["|'].*?>/gm,
                      spanRegex = /(<span.*rangySelectionBoundary.*<\/span>)/gm;
                value = value.replace(emojiRegex, ':$1:').trim();
                value = value.replace(spanRegex, '');
                value = striptags(value, [], '\n').trim();

                return value;
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

            onPaste(e) {
                e.preventDefault();
                let content,
                    command;

                if (e.clipboardData) {
                    content = (e.originalEvent || e).clipboardData.getData('text/plain');

                    exec('insertText', content);
                }

                else if (window.clipboardData) {
                    console.log('hear');
                    content = window.clipboardData.getData('Text');

                    document.selection.createRange().pasteHTML(content);
                }
            }

        }

    }

</script>