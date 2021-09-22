export default () => ({
    theme: 'light',

    init() {
        const [cookie, theme] = document.cookie.match(/theme=(dark|light)/) || [null, null];

        this.theme = theme || (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');

        if (cookie === undefined) {
            this.storeThemeCookie(this.theme);
        }
    },

    toggleTheme() {
        this.theme = this.theme == 'light' ? 'dark' : 'light';
    },

    storeThemeCookie(theme) {
        document.cookie = `theme=${theme}; expires=Fri, 31 Dec 9999 23:59:59 GMT; SameSite=Lax`;
    }
});
