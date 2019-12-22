window.Vue = require('vue');

Vue.component('file-info-modal', require('./components/file-info-modal.vue').default);

const app = new Vue({
    el: "#app",
    methods: {
        showFileInfo(filePath) {
            this.$refs.fileInfoModal.show(filePath);
        }
    }
});

let link = document.getElementById('scroll-to-top');
window.addEventListener('scroll', function() {
    if (window.scrollY > 10) {
        link.classList.remove('hidden');
    } else {
        link.classList.add('hidden');
    }
});
