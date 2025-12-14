import {defineConfig} from "vite";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
  plugins: [vue()],
  build: {
    outDir: "assets", // Output hasil build ke sini
    emptyOutDir: true, // Hapus isi folder assets lama setiap build
    manifest: true, // Wajib untuk PHP agar tahu nama file hash-nya
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, "src/main.js"), // Entry point JS
        style: path.resolve(__dirname, "src/scss/style.scss"), // Entry point CSS
      },
      output: {
        entryFileNames: "js/[name].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith(".css")) {
            return "css/[name].css";
          }
          return "img/[name][extname]";
        },
      },
    },
  },
  server: {
    cors: true,
    strictPort: true,
    port: 3000,
    hmr: {
      host: "localhost",
    },
  },
});
