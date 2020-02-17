window.Vue = require('vue');

Vue.component('file-info-modal', require('./components/file-info-modal.vue').default);

const app = new Vue({
    el: '#app',
    data: function() {
        return {
            search: ''
        };
    },
    methods: {
        showFileInfo(filePath) {
            this.$refs.fileInfoModal.show(filePath);
        }
    },
    beforeMount: function() {
        this.search = this.$el.querySelector('input[name="search"]').value;
    },
    mounted: function() {
        window.addEventListener('keyup', e => e.keyCode == 191 && this.$refs.searchInput.focus());

        let scrollToTop = this.$refs.scrollToTop;
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                scrollToTop.classList.remove('hidden');
            } else {
                scrollToTop.classList.add('hidden');
            }
        });
    }
});

let hljs = require('highlight.js');
hljs.initHighlightingOnLoad();
