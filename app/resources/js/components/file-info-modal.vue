<template>
    <div id="file-info-modal"
        class="fixed top-0 flex justify-center items-center w-screen h-screen p-4 z-50"
        style="background-color: hsla(218, 23%, 23%, 0.5)"
        v-bind:class="this.styles"
        v-on:click.self="hide()"
    >
        <div id="file-info-dialogue" class="bg-white rounded-lg shadow-lg overflow-hidden" v-show="! loading">
            <header class="flex justify-between items-center bg-blue-600 p-4">
                <i class="fas fa-info-circle fa-lg text-white"></i>

                <div class="items-center text-xl text-white font-mono mx-4">
                    {{ title }}
                </div>

                <button
                    class="flex justify-center items-center rounded-full w-6 h-6 text-blue-900 text-sm hover:bg-red-700 hover:text-white hover:shadow"
                    v-on:click="hide()"
                >
                    <i class="fas fa-times"></i>
                </button>
            </header>

            <content class="flex justify-center items-center p-4">
                <div class="overflow-x-auto">
                    <table class="table-auto">
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
                    this.loading = false;
                }.bind(this)).catch(
                    response => this.hide() && console.error(response)
                );
            },
            hide() {
                this.visible = false;
                this.loading = true;
            }
        },
        mounted() {
            window.addEventListener('keyup', e => e.keyCode == 27 && this.hide());
        }
    }
</script>
