<template>

    <div class="conversations-sidebar">
        <user-bar></user-bar>

        <div class="chat-search">
            <b-field>
                <b-autocomplete
                        rounded
                        v-model="name"
                        :data="filteredDataArray"
                        placeholder="Search or start new chat"
                        icon="search"
                        @select="option => selected = option">
                    <template slot="empty">No results found</template>
                </b-autocomplete>
            </b-field>
        </div>

        <div class="messages">

        </div>

        <div class="conversation">

        </div>

    </div>

</template>

<script>

    import UserBar from './UserBar';

    export default {

        data() {
            return {
                data: [
                    'Angular',
                    'Angular 2',
                    'Aurelia',
                    'Backbone',
                    'Ember',
                    'jQuery',
                    'Meteor',
                    'Node.js',
                    'Polymer',
                    'React',
                    'RxJS',
                    'Vue.js'
                ],
                name: '',
                selected: null
            }
        },

        computed: {
            filteredDataArray() {
                return this.data.filter((option) => {
                    return option
                        .toString()
                        .toLowerCase()
                        .indexOf(this.name.toLowerCase()) >= 0
                })
            }
        },

        components: {
            UserBar
        }

    }

</script>