module.exports = {
    'env': {
        'browser': true,
        'es2021': true
    },
    'extends': [
        'eslint:recommended',
    ],
    'parserOptions': {
        'ecmaVersion': 12,
        'sourceType': 'module'
    },
    'rules': {
        'eol-last': ['error', 'always'],
        'indent': ['error', 4],
        'no-multi-spaces': ['error'],
        'quotes': ['error', 'single'],
        'semi': ['error', 'always', { 'omitLastInOneLineBlock': true }],
    }
};
