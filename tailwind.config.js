/** @type {import('tailwindcss').Config} */
/* Theme colors live in resources/css/app.css (@theme). This file is for tooling hints. */
module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        heading: ['Poppins', 'sans-serif'],
      },
      colors: {
        pjp: {
          ink: '#0a0a0a',
          paper: '#ffffff',
          yellow: '#f2e631',
          green: '#2e7d32',
          flame: '#c62828',
          gold: '#e6a000',
          'gold-bright': '#f9b233',
          torch: '#c4956a',
        },
      },
    },
  },
};
