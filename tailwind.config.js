const path = require('path');
const twColors = require('tailwindcss/colors');
const { fontFamily } = require('tailwindcss/defaultTheme');

const colors = {
  black: twColors.black,
  white: twColors.white,
  blue: twColors.blue,
  primary: twColors.sky,
  yellow: twColors.yellow,
  red: twColors.red,
  green: twColors.green,
  gray: twColors.blueGray,
}

function generateColorVariables(colors) {
  return Object.fromEntries(
    Object.entries(colors)
      .map(([key, value]) => {
        if (typeof value === 'string') {
          return [[key, toRgba(value)]]
        }

        return Object.entries(value).map(([shade, hex]) => {
          return [`${key}-${shade}`, toRgba(hex)]
        })
      })
      .flat(1)
      .map(([name, channels]) => {
        return [`--colors-${name}`, channels.slice(0, -1).join(', ')]
      })
  )
}

function colorVar(name) {
  return ({ opacityValue }) => {
    return opacityValue === undefined
      ? `rgb(var(--colors-${name}))`
      : `rgba(var(--colors-${name}), ${opacityValue})`
  }
}

function toColorVariables(colors) {
  return Object.fromEntries(
    Object.entries(colors).map(([key, value]) => {
      if (typeof value === 'string') {
        return [key, colorVar(key)]
      }

      return [
        key,
        Object.fromEntries(
          Object.entries(value).map(([shade]) => {
            return [shade, colorVar(`${key}-${shade}`)]
          })
        ),
      ]
    })
  )
}

module.exports = {
  content: [
    path.resolve(__dirname, 'resources/**/*.{vue,js,ts,jsx,tsx,scss}'),
  ],
  darkMode: 'class',
  prefix: 'nt-',
  important: true,
  theme: {
    colors: {
      ...toColorVariables(
        (({ transparent, current, ...others }) => others)(colors)
      ),
    }
  },
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