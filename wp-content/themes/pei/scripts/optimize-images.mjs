import path from 'node:path';
import { fileURLToPath } from 'node:url';
import fs from 'node:fs/promises';
import imagemin from 'imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminPngquant from 'imagemin-pngquant';
import imageminGifsicle from 'imagemin-gifsicle';
import imageminSvgo from 'imagemin-svgo';
import imageminWebp from 'imagemin-webp';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const themeDir = path.resolve(__dirname, '..');
const wpContentDir = path.resolve(themeDir, '..');
const uploadsDir = path.resolve(wpContentDir, 'uploads');

const isDir = async (p) => {
  try {
    const st = await fs.stat(p);
    return st.isDirectory();
  } catch {
    return false;
  }
};

const gatherFiles = async (root, exts, ignoreDirs = ['node_modules', 'dist', '.git']) => {
  const files = [];
  const stack = [root];
  while (stack.length) {
    const dir = stack.pop();
    let entries = [];
    try {
      entries = await fs.readdir(dir, { withFileTypes: true });
    } catch {
      continue;
    }
    for (const ent of entries) {
      const full = path.join(dir, ent.name);
      if (ent.isDirectory()) {
        if (!ignoreDirs.includes(ent.name)) stack.push(full);
      } else {
        const lower = ent.name.toLowerCase();
        if (exts.some((e) => lower.endsWith(e))) files.push(full);
      }
    }
  }
  return files;
};

const optimizeBatch = async (files) => {
  if (!files.length) return { optimized: 0 };
  const plugins = [
    imageminMozjpeg({ quality: 78 }),
    imageminPngquant({ quality: [0.6, 0.8] }),
    imageminGifsicle({ optimizationLevel: 2 }),
    imageminSvgo({
      plugins: [
        { name: 'preset-default' },
        { name: 'removeViewBox', active: false }
      ]
    })
  ];

  // Process by directory to preserve structure; write in-place
  let count = 0;
  for (const file of files) {
    const dir = path.dirname(file);
    const name = path.basename(file);
    try {
      await imagemin([file], {
        destination: dir,
        plugins
      });
      count++;
    } catch (e) {
      console.warn('Failed to optimize', file, e?.message || e);
    }
  }
  return { optimized: count };
};

async function main() {
  const exts = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp'];
  const targets = [];
  if (await isDir(uploadsDir)) targets.push(uploadsDir);
  targets.push(themeDir);

  let total = 0;
  for (const tgt of targets) {
    const files = await gatherFiles(tgt, exts);
    const { optimized } = await optimizeBatch(files);
    total += optimized;
    console.log(`Optimized ${optimized} images in ${path.relative(themeDir, tgt) || '.'}`);
  }
  console.log(`Done. Total optimized: ${total}`);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
