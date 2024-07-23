const esbuild = require('esbuild');
const postcss = require('postcss');
const fs = require('node:fs');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');

// Build CSS using esbuild
esbuild.build({
    entryPoints: ['view/css/tailwind.css'],
    bundle: true,
    outfile: 'view/css/main.css',
    plugins: [
        {
            name: 'postcss',
            setup(build) {
                build.onLoad({ filter: /\.css$/ }, async (args) => {
                    const css = await fs.promises.readFile(args.path, 'utf8');
                    const result = await postcss([tailwindcss, autoprefixer])
                        .process(css, { from: args.path, to: 'view/css/main.css' });
                    return {
                        contents: result.css,
                        loader: 'css',
                    };
                });
            },
        },
    ],
}).catch(() => process.exit(1));