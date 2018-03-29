<template>

    <div class="chat" @scroll="onScroll">

        <div class="message-rows">
            <div class="message-row loading-row" v-show="loading">
                <div class="loading-icon"></div>
            </div>
            
            <div class="message-row" v-for="message in messages">

                <message :message="message"></message>

            </div>
        </div>

    </div>

</template>

<script>
    import zenscroll from 'zenscroll';
    import debounce from 'lodash/debounce';
    import Message from './Message';

    export default {

        props: [
            'messages',
            'conversationId',
            'loading'
        ],

        data() {
            return {
                allowAutoScroll: true,
                scroller: null,
                firstLoad: true
            }
        },

        mounted() {
            this.scroller = zenscroll.createScroller(this.$el, 200, 0);
            this.scrollToEnd(false);
        },

        watch: {
            loading() {
                this.firstLoad = true;
            },

            conversationId() {
                this.allowAutoScroll = true;
                this.firstLoad = true;
            },

            messages() {
                this.$nextTick(function() {
                    this.scrollToEnd(! this.firstLoad);
                    this.firstLoad = false;
                });
            }
        },

        methods: {

            onScroll: debounce(function(e) {
                const el = e.target,
                    scrollHeight = el.scrollHeight,
                    clientHeight = el.clientHeight,
                    offset = 50,
                    scrollTop = el.scrollTop;

                this.allowAutoScroll = scrollTop + clientHeight + offset >= scrollHeight;
            }, 20),

            scrollToEnd(animate = true) {
                if(! this.allowAutoScroll || ! this.scroller) {
                    return;
                }

                // if(! animate || ! this.scroller) {
                //
                //     return;
                // }

                this.$el.scrollTop = this.$el.scrollHeight;
                //this.scroller.toY(this.$el.scrollHeight);
            }

        },

        components: {
            Message
        }

    }

</script>