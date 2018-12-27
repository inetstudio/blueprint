let packagesPath = './vendor/inetstudio',
    search = true;

let mix = require('laravel-mix');

if (search) {
    let fileSystem = require('fs');
    let find = require('find');

    let files = find.fileSync(/app\.scss/, packagesPath);
    let content = '';

    files.forEach(function (file) {
        content += '@import "../../'+file+'";\n';
    });

    try {
        fileSystem.writeFileSync('resources/sass/app.scss', content);
    } catch (e) {
        console.log("Cannot write file ", e);
    }
}

mix.autoload({
    jquery: ['$', 'jQuery']
});

mix
    .js([
        packagesPath+'/admin-panel/resources/assets/js/app.js'
    ], 'admin/js/app.js')
    .extract([
        'jquery',
        'bootstrap-sass',
        'vue'
    ])
    .sass('resources/sass/app.scss', 'admin/css/app.css', {
        implementation: require('node-sass')
    })
    .options({
        fileLoaderDirs: {
            fonts: 'admin/fonts',
            images: 'admin/images'
        }
    })
    .version();
