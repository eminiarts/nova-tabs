module.exports = {
  printWidth: 80,
  tabWidth: 2,
  useTabs: false,
  singleQuote: true,
  trailingComma: 'es5',
  bracketSpacing: true,
  jsxBracketSameLine: false,
  semi: false,
  requirePragma: false,
  proseWrap: 'preserve',
  arrowParens: 'avoid',

  overrides: [
    {
      files: 'resources/css/**/*.css',
      options: {
        tabWidth: 2,
      },
    },
    {
      files: [
        'resources/js/fields.js',
        'resources/js/components.js',
        'resources/js/router/routes.js',
      ],
      options: {
        printWidth: 300,
      },
    },
  ],
}
