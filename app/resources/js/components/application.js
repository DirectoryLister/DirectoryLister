export default () => ({
    theme: 'light',
    loading: true,

    init() {
        this.theme = localStorage.theme || (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    },

    toggleTheme() {
        this.theme = this.theme == 'light' ? 'dark' : 'light';
    },
});
