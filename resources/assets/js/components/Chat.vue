<template>

    <div class="chat" @scroll="onScroll">

        <div class="message-rows">
            <div class="message-row loading-row" :style="{opacity: loading ? 1 : 0}">
                <div class="loading-icon"></div>
            </div>

            <template v-for="group in messages">
                <div :key="group.date"
                     class="message-row is-centered">
                    <b-tag type="is-dark">
                        {{ group.date | date }}
                    </b-tag>
                </div>

                <div class="message-row"
                     v-for="message in group.messages"
                     :key="message.id"
                     :class="{'is-mine': message.mine}">

                    <message :message="message" :class="'message-' + message.id"></message>

                </div>
            </template>

        </div>

    </div>

</template>

<script>
    import zenscroll from 'zenscroll';
    import debounce from 'lodash/debounce';
    import Message from './Message';
    import moment from "moment";

    export default {

        props: [
            'conversationId',
            'conversation',
            'loading'
        ],

        data() {
            return {
                allowAutoScroll: true,
                lastScrollTop: null,
                scroller: null
            }
        },

        mounted() {
            this.scroller = zenscroll.createScroller(this.$el, 200, 0);
            this.scrollToEnd(false);
        },

        watch: {
            conversationId() {
                this.allowAutoScroll = true;
            },

            messages: {
                handler() {
                    this.$nextTick(function() {
                        this.scrollToEnd(! this.conversation.isMessagesLoaded());

                        if(this.conversation.isMessagesLoaded() && this.conversation.getScrollPosition(this.$el)) {
                            this.$el.scrollTop = this.conversation.getScrollPosition(this.$el);
                        }
                    });
                },
                deep: true
            }
        },

        methods: {

            onScroll: debounce(function(e) {
                const el = e.target,
                    scrollHeight = el.scrollHeight,
                    clientHeight = el.clientHeight,
                    offset = 50,
                    scrollTop = el.scrollTop;

                this.lastScrollTop = scrollTop;

                if(scrollTop <= 100) {
                    this.$emit('load-more');
                }

                this.allowAutoScroll = scrollTop + clientHeight + offset >= scrollHeight;

                if(! this.allowAutoScroll) {
                    this.conversation.setScrollPosition(this.$el);
                } else {
                    this.conversation.resetScrollPosition();
                }
            }, 20),

            scrollToEnd(animate = true) {
                if(! this.allowAutoScroll || ! this.scroller) {
                    return;
                }

                if(! animate || ! this.scroller) {
                    this.$el.scrollTop = this.$el.scrollHeight;

                    return;
                }

                this.scroller.toY(this.$el.scrollHeight);
            }

        },

        computed: {
            messages() {
                return this.conversation.messages;
            }
        },

        filters: {
            date: function (value) {
                if (!value) {
                    return '';
                }

                let date = moment(value);

                switch (moment().diff(date, 'days')) {
                    case 0:
                        return 'Today';
                    case 1:
                        return 'Yesterday';
                    default:
                        if(moment().diff(date, 'years')) {
                            return date.format('MMMM D YYYY');
                        }

                        return date.format('MMMM D');
                }
            }
        },

        components: {
            Message
        }

    }

</script>