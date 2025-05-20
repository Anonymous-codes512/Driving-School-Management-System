import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',   // your main CSS
        'resources/js/app.js',     // your main JS
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
  build: {
    manifest: true,
    outDir: 'public/build',
  },
})
