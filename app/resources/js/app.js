import Vue from 'vue';
import FileInfoModal from './components/file-info-modal.vue';

const app = new Vue({
    el: '#app',
    components: { FileInfoModal },
    data: () => ({
        theme: 'light',
        menuOpen: false,
    }),
    computed: {
        darkMode() {
            return this.theme === 'dark';
        },
        lightMode() {
            return this.theme === 'light';
        }
    },
    methods: {
        showFileInfo(filePath) {
            this.$refs.fileInfoModal.show(filePath);
        },
        toggleTheme() {
            this.theme = this.lightMode ? 'dark' : 'light';
        },
    },
    created: function () {
        this.theme = localStorage.getItem('theme') || 'light';
    },
    mounted: function() {
        window.addEventListener('keyup', e => e.key === '/' && this.$refs.searchInput.focus());

        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                this.$refs.scrollToTop.classList.remove('hidden');
            } else {
                this.$refs.scrollToTop.classList.add('hidden');
            }
        }.bind(this));
    },
    watch: {
        theme: value => localStorage.setItem('theme', value),
    }
});

let hljs = require('highlight.js');
hljs.initHighlightingOnLoad();
