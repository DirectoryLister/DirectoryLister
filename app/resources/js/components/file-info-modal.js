import axios from 'axios';

export default () => ({
    error: null,
    filePath: 'file.info',
    hashes: null,
    visible: false,

    get title() {
        return this.filePath.split('/').pop();
    },

    get loading() {
        return this.hashes === null;
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
        this.hashes = null;
        this.error = null;
    }
});
