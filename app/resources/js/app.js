import Vue from 'vue';
import FileInfoModal from './components/file-info-modal.vue';

const app = new Vue({
    el: '#app',
    components: { FileInfoModal },
    data: () => ({
        loading: true,
        theme: 'light',
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
        copyUrl(tFileUrl, fileUrlPrefix) {
            let origin = (!fileUrlPrefix.includes('http'))
                ? location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '')
                : '';
            let inputElement = document.createElement('input');
            inputElement.value = origin+tFileUrl;
            inputElement.style.display = 'block';
            document.body.appendChild(inputElement);
            inputElement.select();
            document.execCommand("copy");
            inputElement.remove();
        },
        toggleTheme() {
            this.theme = this.lightMode ? 'dark' : 'light';
        },
    },
    created: function () {
        this.theme = localStorage.theme || (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
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

        this.loading = false;
    },
    watch: {
        theme: value => localStorage.setItem('theme', value),
    }
});

let hljs = require('highlight.js');
hljs.initHighlightingOnLoad();
