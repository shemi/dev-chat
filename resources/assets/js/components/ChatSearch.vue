<template>

    <div class="chat-search">
        <b-field>
            <b-autocomplete
                    rounded
                    :data="data"
                    v-model.trim="name"
                    placeholder="Search or start new chat"
                    icon="search"
                    field="name"
                    :loading="isSearching"
                    @input="search"
                    @select="option => selected = option">

                <template slot-scope="props">
                    <div class="media">
                        <div class="media-left">
                            <img width="32" :src="props.option.image">
                        </div>
                        <div class="media-content">
                            @{{ props.option.username }}
                            <br>
                            <small>
                                {{ props.option.name }}
                            </small>
                        </div>
                    </div>
                </template>

                <template slot="empty">
                    <p v-if="! isSearching">
                        <span v-if="name.indexOf('@') !== -1">
                            {{ name.length > 1 ? 'No users matching your search' : 'keep typing...' }}
                        </span>
                        <span v-else>
                            No results
                        </span>
                    </p>
                    <p v-else>
                        Searching...
                    </p>
                </template>
            </b-autocomplete>
        </b-field>
    </div>

</template>

<script>
    import debounce from 'lodash/debounce';

    export default {

        data() {
            return {
                name: '',
                selected: null,
                isSearching: false,
                data: [],
            }
        },

        methods: {

            search: debounce(function () {
                this.data = [];
                this.isSearching = true;

                axios.get(window.App.api_base + '/search/' + this.name)
                    .then(({ data }) => {
                        data.data.forEach((item) => this.data.push(item));
                        this.isSearching = false;
                    })
                    .catch(({ data }) => {
                        this.isSearching = false;
                        console.log(data);
                    })

            }, 500)

        }

    }

</script>