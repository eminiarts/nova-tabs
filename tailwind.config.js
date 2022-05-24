const path = require('path');
const colors = require('tailwindcss/colors');
const {fontFamily} = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    path.resolve(__dirname, 'resources/**/*.{vue,js,ts,jsx,tsx,scss}'),
  ],
  safelist: [
    'tabs-border-b-gray-500',
    'tabs-border-b-slate-500',
    'tabs-border-b-zinc-500',
    'tabs-border-b-neutral-500',
    'tabs-border-b-stone-500',
    'tabs-border-b-red-500',
    'tabs-border-b-orange-500',
    'tabs-border-b-amber-500',
    'tabs-border-b-yellow-500',
    'tabs-border-b-lime-500',
    'tabs-border-b-green-500',
    'tabs-border-b-emerald-500',
    'tabs-border-b-teal-500',
    'tabs-border-b-cyan-500',
    'tabs-border-b-sky-500',
    'tabs-border-b-blue-500',
    'tabs-border-b-indigo-500',
    'tabs-border-b-violet-500',
    'tabs-border-b-purple-500',
    'tabs-border-b-fuchsia-500',
    'tabs-border-b-pink-500',
    'tabs-border-b-rose-500',

    'tabs-text-gray-500',
    'tabs-text-slate-500',
    'tabs-text-zinc-500',
    'tabs-text-neutral-500',
    'tabs-text-stone-500',
    'tabs-text-red-500',
    'tabs-text-orange-500',
    'tabs-text-amber-500',
    'tabs-text-yellow-500',
    'tabs-text-lime-500',
    'tabs-text-green-500',
    'tabs-text-emerald-500',
    'tabs-text-teal-500',
    'tabs-text-cyan-500',
    'tabs-text-sky-500',
    'tabs-text-blue-500',
    'tabs-text-indigo-500',
    'tabs-text-violet-500',
    'tabs-text-purple-500',
    'tabs-text-fuchsia-500',
    'tabs-text-pink-500',
    'tabs-text-rose-500',
  ],
  darkMode: 'class',
  prefix: 'tabs-',
  theme: {
    extend: {
      colors: {
        gray: colors.slate
      }
    }
  },
  important: true,
  plugins: [],
};

