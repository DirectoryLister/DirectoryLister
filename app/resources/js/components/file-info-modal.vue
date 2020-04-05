<template>
    <div id="file-info-modal" v-bind:class="this.styles" v-on:click.self="hide()"
        class="fixed top-0 flex justify-center items-center w-screen h-screen p-4 z-50"
        style="background-color: hsla(218, 23%, 23%, 0.5)"
    >
        <div id="file-info-dialogue" v-show="! loading"
            class="transition duration-500 ease-in-out bg-white rounded-lg shadow-lg overflow-hidden"
        >
            <header class="flex justify-between items-center bg-blue-600 p-4">
                <i class="fas fa-info-circle fa-lg text-white"></i>

                <div class="items-center text-xl text-white font-mono mx-4">
                    {{ title }}
                </div>

                <button v-on:click="hide()"
                    class="flex justify-center items-center rounded-full w-6 h-6 text-blue-900 text-sm hover:bg-red-700 hover:text-white hover:shadow"
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
                            <tr v-for="(hash, title) in this.hashes" v-bind:key="hash">
                                <td class="border font-bold px-4 py-2">{{ title }}</td>
                                <td class="border font-mono px-4 py-2">{{ hash }}</td>
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
                hashes: {
                    'md5': '••••••••••••••••••••••••••••••••',
                    'sha1': '••••••••••••••••••••••••••••••••••••••••',
                    'sha256': '••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••'
                },
                loading: true,
                visible: false,
            };
        },
        computed: {
            styles() {
                return { 'hidden': ! this.visible };
            },
            title() {
                return this.filePath.split('/').pop();
            }
        },
        methods: {
            async show(filePath) {
                this.filePath = filePath;
                this.visible = true;

                await axios.get('?info=' + filePath).then(function (response) {
                    this.hashes = response.data.hashes;
                }.bind(this)).catch(function (error) {
                    this.error = error.response.data.message;
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
            window.addEventListener('keyup', e => e.keyCode == 27 && this.hide());
        }
    }
</script>
