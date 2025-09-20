/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./app/**/*.{js,ts,jsx,tsx}", // add this if your components live in app/
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
