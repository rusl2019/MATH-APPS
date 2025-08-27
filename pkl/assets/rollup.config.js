// rollup.config.js
import resolve from "@rollup/plugin-node-resolve";
import commonjs from "@rollup/plugin-commonjs";
import terser from "@rollup/plugin-terser";
import postcss from "rollup-plugin-postcss";
import postcssImport from 'postcss-import';

export default {
  input: "src/js/main.js", // File entri utama Anda
  output: {
    file: "dist/bundle.js", // File hasil bundle
    format: "iife", // Format output (cocok untuk browser)
    sourcemap: true,
  },
  plugins: [
    resolve(),
    commonjs(),
    terser(), // Minify JavaScript
    postcss({
      plugins: [
        postcssImport(), // Plugin untuk menangani @import
      ],
      extract: true, // Ekstrak CSS ke file terpisah
      minimize: true,
      sourceMap: true,
    }),
  ],
};
