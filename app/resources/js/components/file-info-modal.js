import axios from 'axios';

export default () => ({
    error: null,
    filePath: 'file.info',
    hashes: {
        md5: '••••••••••••••••••••••••••••••••',
        sha1: '••••••••••••••••••••••••••••••••••••••••',
        sha256: '••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••'
    },
    loading: false,
    visible: false,

    get title() {
        return this.filePath.split('/').pop();
    },

    async show(filePath) {
        this.filePath = filePath;
        this.loading = true;
        this.visible = true;

        try {
            var response = await axios.get('?info=' + filePath);
        } catch (error) {
            this.error = error.response.request.statusText;
            this.loading = false;
        }

        this.hashes = response.data.hashes;
        this.loading = false;
    },

    hide() {
        this.visible = false;
        this.loading = false;
        this.error = null;
    }
});
