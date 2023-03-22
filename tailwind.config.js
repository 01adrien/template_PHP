/** @type {import('tailwindcss').Config} */

const plugin = require('tailwindcss/plugin')

module.exports = {
  content: [
    "./src/App/views/**/*.twig",
  ],
  /*
  purge: {
    enabled: true,
    content: [""]
  },
  */
  theme: {
    extend: {
      screens: {},
      colors: {
        primary: {
          DEFAULT: "",
          light: "",
          dark:""
        }
      },
      fontFamily: {
        customFont: ""
      },
      spacing: {},
      animation: {},
      fontWeight: {},
      fontSize: {},
      variants: {
          backgroundColor: ['active']
        },
    },
  },
}
