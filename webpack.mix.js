require('dotenv').config();

let packagesPath = './vendor/inetstudio',
    search = true;

let lodash = require('lodash');
let path = require('path');
let mix = require('laravel-mix');
let File = require('laravel-mix/src/File');
let find = require('find');

global.Mix.manifest.path = function () {
    return path.join('public/admin', this.name);
};

global.Mix.manifest.hash = function (file) {
    let hash = new File(path.join(Config.publicPath, file)).version();

    let filePath = this.normalizePath(file);

    this.manifest[filePath] = filePath.replace('/admin/', '') + '?id=' + hash;

    return this;
};

if (search) {
    let fileSystem = require('fs');

    let files = find.fileSync(/app\.scss/, packagesPath);
    let content = '@import "./admin/project";\n';

    files.forEach(function (file) {
        content += '@import "../../../'+file+'";\n';
    });

    try {
        fileSystem.writeFileSync('resources/assets/sass/app.scss', content);
    } catch (e) {
        console.log("Cannot write file ", e);
    }
}

let webpackConfig = {
    output: {
        chunkFilename: 'admin/js/chunks/[chunkhash].js',
    },
};

let webpackConfigFiles = find.fileSync(/package\.webpack\.config\.js/, packagesPath);

webpackConfigFiles.forEach(function (file) {
    const config = require(__dirname+'/'+file);

    webpackConfig = lodash.merge(webpackConfig, config);
});

mix
    .webpackConfig(webpackConfig)
    .setResourceRoot(process.env.APP_URL)
    .autoload({
        jquery: ['$', 'jQuery']
    })
    .js([
        packagesPath+'/admin-panel/resources/assets/js/app.js',
        'packages/receipts-contest/entities/receipts/resources/assets/js/app.js',
        'resources/assets/js/admin/project/app.js',
    ], 'admin/js/app.js')
    .vue({
        extractStyles: true,
        globalStyles: false
    })
    .extract([
        'jquery',
        'bootstrap',
        'vue'
    ])
    .sass('resources/assets/sass/app.scss', 'admin/css/app.css', {
        implementation: require('node-sass')
    })
    .options({
        fileLoaderDirs: {
            fonts: 'admin/fonts',
            images: 'admin/img'
        },
        uglify: {
            parallel: 8, // Use multithreading for the processing
            uglifyOptions: {
                mangle: true,
                compress: false, // The slow bit
            }
        },
        terser: {
            extractComments: (astNode, comment) => false,
            terserOptions: {
                format: {
                    comments: false,
                }
            }
        }
    })
    .version();
