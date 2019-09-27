let packagesPath = './vendor/inetstudio',
    search = true;

let mix = require('laravel-mix');
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
    let find = require('find');

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

mix.autoload({
    jquery: ['$', 'jQuery']
});

mix
    .js([
        packagesPath+'/admin-panel/resources/assets/js/app.js',
        'resources/assets/js/admin/project/app.js',
    ], 'admin/js/app.js')
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
        }
    })
    .version();
