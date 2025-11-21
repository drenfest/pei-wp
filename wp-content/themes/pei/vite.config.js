import { defineConfig } from 'vite';
import { globby } from 'globby';
import path from 'node:path';

// Build multiple entry points: main.js and each component's JS file.
async function getInput() {
  const entries = {};
  // Main entry
  entries['src/main.js'] = path.resolve('src/main.js');
  // Component entries
  const files = await globby('src/components/*/*.js');
  files.forEach((file) => {
    // keep key as source path so it matches manifest keys we expect in PHP
    const rel = file.replace(/\\/g, '/');
    entries[rel] = path.resolve(file);
  });
  return entries;
}

export default defineConfig(async () => ({
  root: '.',
  base: '/wp-content/themes/pei/dist/',
  publicDir: false,
  css: {
    devSourcemap: true,
    preprocessorOptions: {
      scss: {
        api: 'modern'
      }
    }
  },
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    sourcemap: true,
    rollupOptions: {
      input: await getInput(),
      output: {
        entryFileNames: 'assets/[name].[hash].js',
        chunkFileNames: 'assets/[name].[hash].js',
        assetFileNames: ({ name }) => {
          if (name && name.endsWith('.css')) return 'assets/[name].[hash][extname]';
          return 'assets/[name].[hash][extname]';
        }
      }
    }
  }
}));
