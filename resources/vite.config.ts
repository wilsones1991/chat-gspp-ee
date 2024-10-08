import { defineConfig } from "vite"
import path from "path"
import react from "@vitejs/plugin-react"

export default defineConfig({
  build: {
    // generate .vite/manifest.json in outDir
    emptyOutDir: true,
    manifest: true,
    outDir: path.resolve(__dirname, "../themes/user/chat-gspp/"),
    rollupOptions: {
      // overwrite default .html entry
      input: '/src/main.tsx',
    },
  },
  plugins: [react()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
  server: {
    origin: 'http://localhost:5173'
  }
})
