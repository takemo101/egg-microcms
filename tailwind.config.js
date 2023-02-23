/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resource/**/*.latte.html',
  ],
  theme: {
    extend: {
      typography: {
        DEFAULT: {
          css: {
            maxWidth: '100%', // add required value here
          }
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
