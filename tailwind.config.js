/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./includes/**/*.php", // Scan file PHP di folder includes
    "./src/**/*.{vue,js,ts,jsx,tsx}", // Scan file Vue/JS di folder src
    "./*.php", // Scan file PHP utama di root
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
