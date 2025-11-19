/** @type {import('tailwindcss').Config} */
import flowbite from "flowbite/plugin";
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
    plugins: [
    flowbite
],
}
