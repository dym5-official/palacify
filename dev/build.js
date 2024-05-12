import path from 'node:path';
import fs from 'node:fs';
import esbuild from 'esbuild';
import uniqcss from 'uniqcss';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const entryFile = path.join(__dirname, '..', 'dev', 'index.jsx');
const outDir = path.join(__dirname, '..', 'assets', 'admin');

(async () => {
    ["bundle.css.map", "bundle.js.map"].forEach((file) => {
        const remFile = path.join( outDir, file );

        if ( fs.existsSync( remFile ) ) {
            fs.rmSync( remFile );
        }
    });

    await esbuild.build({
        entryPoints: [entryFile],
        bundle: true,
        minify: true,
        sourcemap: process.env.NODE_ENV !== "production",
        format: 'iife',
        outfile: path.join(outDir, 'bundle.js'),
        jsxFactory: "h",
        jsxFragment: "Fragment",

        loader: {
            '.woff2': 'file',
            '.ttf': 'file',
            '.svg': 'file',
            '.png': 'file',
        },

        plugins: [
            uniqcss()
        ]
    });
})();