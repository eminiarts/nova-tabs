module.exports = {
    plugins: [
        'stylelint-order',
    ],
    rules: {
        indentation: 4,
        'rule-empty-line-before': [ 'always', {
            ignore: [
                'first-nested',
            ],
        }],
        'order/order': [
            'custom-properties',
            'dollar-variables',
            'declarations',
            'rules',
            'at-rules',
        ],
    },
};
