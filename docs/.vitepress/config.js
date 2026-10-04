import { defineConfig } from 'vitepress';
import { tabsMarkdownPlugin } from 'vitepress-plugin-tabs';

const head = [
    ['link', { rel: 'icon', href: '/images/directory-lister.svg' }],
];

if (process.env.ENVIRONMENT === 'production') {
    head.push(['script', { defer: '', src: 'https://analytics.phlak.net/script.js', 'data-website-id': '23fea9c6-c39e-44ce-bada-5a74c2d207a7' }]);
}

export default defineConfig({
    title: 'Directory Lister Docs',
    description: 'The official Directory Lister documentation.',

    head: head,

    markdown: {
        config(md) {
            md.use(tabsMarkdownPlugin);
        },
    },

    themeConfig: {
        logo: '/images/directory-lister.svg',

        nav: [
            { text: 'Help & Support', link: 'https://github.com/DirectoryLister/DirectoryLister/discussions' },
            { text: 'Changelog', link: 'https://github.com/DirectoryLister/DirectoryLister/releases' },
        ],

        sidebar: [
            { text: 'Introduction', link: '/' },
            { text: 'Installation', link: '/installation' },
            { text: 'Upgrade Guide', link: '/upgrade-guide' },
            {
                text: 'Configuration',
                items: [
                    { text: 'Configuration Overview', link: '/configuration/' },
                    { text: 'Configuration Reference', link: '/configuration/configuration-reference' },
                    { text: 'Advanced Configuration', link: '/configuration/advanced-configuration' },
                    { text: 'File Matching Patterns', link: '/configuration/file-matching-patterns' },
                    { text: 'Authentication', link: '/configuration/authentication' }
                ]
            },
            {
                text: 'Help & Support',
                items: [
                    { text: 'Troubleshooting', link: '/help-and-support/troubleshooting' },
                    { text: 'Common Issues', link: '/help-and-support/common-issues' }
                ]
            },
            {
                text: 'Developers',
                items: [
                    { text: 'Development Environment', link: '/developers/development-environment' }
                ]
            }
        ],

        outline: { level: [2, 4] },

        search: { provider: 'local' },

        socialLinks: [
            { icon: 'bluesky', link: 'https://bsky.app/profile/directorylister.com' },
            { icon: 'github', link: 'https://github.com/DirectoryLister/DirectoryLister' }
        ],

        editLink: {
            pattern: 'https://github.com/DirectoryLister/DirectoryLister/edit/master/docs/:path'
        },

        lastUpdated: true,
    }
});
