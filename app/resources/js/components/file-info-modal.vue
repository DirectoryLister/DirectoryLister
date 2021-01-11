<template>
    <div id="file-info-modal" v-bind:class="this.styles" v-on:click.self="hide()"
        class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 p-4 z-50 dark:bg-gray-600 dark:bg-opacity-50"
    >
        <div id="file-info-dialogue" v-show="! loading"
            class="bg-white rounded-lg shadow-lg overflow-hidden dark:bg-gray-800 dark:text-white" style="overflow-wrap: anywhere;"
        >
            <header class="flex justify-between items-center bg-blue-600 p-4 dark:bg-purple-700">
                <i class="fas fa-info-circle fa-lg text-white"></i>

                <div class="items-center text-xl text-white font-mono mx-4">
                    {{ title }}
                </div>

                <button v-on:click="hide()"
                    class="flex justify-center items-center rounded-full w-6 h-6 text-gray-900 text-opacity-50 text-sm hover:bg-red-700 hover:text-white hover:shadow"
                >
                    <i class="fas fa-times"></i>
                </button>
            </header>

            <content class="flex justify-center items-center p-4">
                <div class="overflow-x-auto">
                    <p class="font-thin text-2xl text-gray-600 m-4" v-if="error">
                        {{ error }}
                    </p>

                    <table class="table-auto" v-else>
                        <tbody>
                            <tr v-for="(fileMetaValue, fileMetaKey) in this.fileMeta" v-bind:key="fileMetaKey">
                                <td class="border font-bold px-4 py-2">{{ fileMetaKey }}</td>
                                <td class="border font-mono px-4 py-2">{{ fileMetaValue }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </content>
        </div>

        <i class="fas fa-spinner fa-pulse fa-5x text-white" v-show="loading"></i>
    </div>
</template>

<script>
    const axios = require('axios').default;

    export default {
        data: function () {
            return {
                error: null,
                filePath: 'file-info.txt',
                fileMeta: {},
                loading: true,
                visible: false,
            };
        },
        computed: {
            styles() {
                return { 'hidden': ! this.visible };
            },
            title() {
                return decodeURIComponent(this.filePath.split('/').pop());
            }
        },
        methods: {
            async show(filePath) {
                this.filePath = filePath;
                this.visible = true;

                await axios.get('?info=' + filePath).then(function (response) {
                    this.fileMeta = response.data;
                }.bind(this)).catch(function (error) {
                    this.error = error.response.request.statusText;
                }.bind(this));

                this.loading = false;
            },
            hide() {
                this.visible = false;
                this.loading = true;
                this.error = null;
            }
        },
        mounted() {
            window.addEventListener('keyup', e => e.key == 'Escape' && this.hide());
        }
    }
</script>
