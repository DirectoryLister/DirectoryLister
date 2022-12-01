import axios from 'axios';

export default () => ({
    error: null,
    filePath: 'file.info',
    hashes: {},
    visible: false,

    get title() {
        return decodeURI(this.filePath.split('/').pop());
    },

    get loading() {
        return Object.keys(this.hashes).length === 0;
    },

    async show(filePath) {
        this.filePath = filePath;
        this.visible = true;

        try {
            var response = await axios.get('?info=' + filePath);
        } catch (error) {
            this.error = error.response.request.statusText;
            this.loading = false;
        }

        this.hashes = response.data.hashes;
    },

    hide() {
        this.visible = false;
        this.hashes = {};
        this.error = null;
    }
});
