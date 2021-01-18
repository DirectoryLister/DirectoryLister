export default {
    'env': {
        'browser': true,
        'es2021': true
    },
    'extends': [
        'eslint:recommended',
        'plugin:vue/essential'
    ],
    'parserOptions': {
        'ecmaVersion': 12,
        'sourceType': 'module'
    },
    'plugins': ['vue'],
    'rules': {
        'quotes': ['error', 'single'],
        'semi': ['always', { 'omitLastInOneLineBlock': true }],
    }
};
