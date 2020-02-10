window.Vue = require('vue');

Vue.component('file-info-modal', require('./components/file-info-modal.vue').default);

const app = new Vue({
    el: "#app",
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
    }
});

let hljs = require('highlight.js');
hljs.initHighlightingOnLoad();

let link = document.getElementById('scroll-to-top');
window.addEventListener('scroll', function() {
    if (window.scrollY > 10) {
        link.classList.remove('hidden');
    } else {
        link.classList.add('hidden');
    }
});
