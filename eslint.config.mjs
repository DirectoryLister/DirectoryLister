import globals from 'globals';
import js from '@eslint/js';

export default [
    js.configs.recommended,
    {
        files: ['**/*.js'],
        languageOptions: {
            ecmaVersion: 12,
            sourceType: 'module',
            globals: {
                ...globals.amd,
                ...globals.browser,
                ...globals.commonjs,
                ...globals.es2021,
            }
        },
        'rules': {
            'eol-last': ['error', 'always'],
            'indent': ['error', 4],
            'no-multi-spaces': ['error'],
            'quotes': ['error', 'single'],
            'semi': ['error', 'always', { 'omitLastInOneLineBlock': true }],
        }
    }
];
