module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true,
    },
    globals: {
        Nova: 'readonly',
        _: 'readonly',
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/essential',
    ],
    parserOptions: {
        ecmaVersion: 12,
        sourceType: 'module',
    },
    plugins: [
        'vue',
    ],
    rules: {
        indent: [
            'error',
            4,
        ],
        'linebreak-style': [
            'error',
            'unix',
        ],
        quotes: [
            'error',
            'single',
        ],
        semi: [
            'error',
            'always',
        ],
        'comma-dangle': [
            'error',
            'always-multiline',
        ],
        'quote-props': [
            'error',
            'as-needed',
        ],
        'vue/html-indent': [
            'error',
            4,
        ],
        'vue/custom-event-name-casing': 'off', // Nova events are named this way
        eqeqeq: [ 'error', 'always' ],
        'keyword-spacing': [ 'error' ],
        'prefer-const': [ 'error' ],
        'no-var': [ 'error' ],
    },
};
