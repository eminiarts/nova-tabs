const path = require('path');
const { fontFamily } = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    path.resolve(__dirname, 'resources/**/*.{vue,js,ts,jsx,tsx,scss}'),
  ],
  darkMode: 'class',
  prefix: 'tabs-',
  important: true,
  plugins: [],
  safelist: [
    'grid-cols-1',
    'grid-cols-2',
    'grid-cols-3',
    'grid-cols-4',
    'grid-cols-5',
    'grid-cols-6',
    'grid-cols-7',
    'grid-cols-8',
    'grid-cols-9',
    'grid-cols-10',
    'grid-cols-11',
    'grid-cols-12',
    'grid-cols-none',
    'grid'
  ],
};
