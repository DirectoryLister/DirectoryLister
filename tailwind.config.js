module.exports = {
    plugins: [],
    purge: {
        mode: 'all',
        content: [
            'app/**/*.js',
            'app/**/*.php',
            'app/**/*.scss',
            'app/**/*.twig',
            'app/**/*.vue',
        ],
        options: {
            whitelist: ['html', 'body', 'main', 'fab', 'fas'],
            whitelistPatterns: [/^fa\-/, /^hljs/]
        }
    },
    theme: {
        extend: {
            fontFamily: {
                mono: [
                    'Source Code Pro',
                    'Menlo',
                    'Monaco',
                    'Consolas',
                    '"Liberation Mono"',
                    '"Courier New"',
                    'monospace',
                ],
                sans: [
                    'Work Sans',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    '"Helvetica Neue"',
                    'Arial',
                    '"Noto Sans"',
                    'sans-serif',
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ]
            },
            textColor: {
                github: '#171515',
                spectrum: '#7B16FF',
                twitter: '#1DA1F2'
            }
        }
    },
    variants: {
        visibility: ['responsive', 'hover', 'group-hover']
    }
};
