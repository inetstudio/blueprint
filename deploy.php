<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/rsync.php';

set('keep_releases', 5);

// Project repository
set('project_alias', 'project');
set('repository', 'git@bitbucket.org:inet-studio/project.git');
set('bin/php', function () {
    return '/usr/bin/php8.0';
});

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', [
    'storage/app/public/receipts_contest',
    'storage/app/public/receipts_contest/receipts',
    'storage/app/public/pages',
    'storage/app/public/temp',
    'storage/app/public/users',
    'storage/uploads',
    'vendor',
]);


// Hosts
set('default_stage', 'test');
inventory('hosts.yaml');

// Tasks
task('artisan:optimize', function () {});

task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        warning('To speed up composer installation setup "unzip" command with PHP zip extension.');
    }
    run('cd {{release_path}} && {{bin/composer}} install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs 2>&1');
});

// Copy assets to host
task('copy:assets', function () {
    upload(__DIR__.'/public/admin/', '{{release_path}}/public/admin/');
});

task('copy:release', function () {
    upload('release', '{{release_path}}/release');
});

// Deploy
desc('Prepare environment');
task('files:test_environment', function () {
    run('mv {{release_path}}/.env.test {{release_path}}/.env');
    run('mv {{release_path}}/public/robots.txt.test {{release_path}}/public/robots.txt');
    run('rm {{release_path}}/.env.production');
    run('rm {{release_path}}/.env.local');
    run('rm {{release_path}}/public/robots.txt.production');
    run("cd {{release_path}} && chmod +x artisan");
    run("cd {{release_path}}/public && chmod 777 sitemap.xml");
})->onStage('test');
after('deploy:shared', 'files:test_environment');

task('files:prod_environment', function () {
    run('mv {{release_path}}/.env.production {{release_path}}/.env');
    run('mv {{release_path}}/public/robots.txt.production {{release_path}}/public/robots.txt');
    run('rm {{release_path}}/.env.test');
    run('rm {{release_path}}/.env.local');
    run('rm {{release_path}}/public/robots.txt.test');
    run("cd {{release_path}} && chmod +x artisan");
    run("cd {{release_path}}/public && chmod 777 sitemap.xml");
})->onStage('production');
after('deploy:shared', 'files:prod_environment');

after('deploy:shared', 'copy:assets');
after('copy:assets', 'copy:release');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    run('sudo systemctl restart php8.0-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

desc('Restart queue service');
task('queue:restart', function () {
    run('sudo systemctl restart queue_'.get('project_alias'));
});
after('php-fpm:restart', 'queue:restart');

before('deploy:symlink', 'deploy:public_disk');
before('deploy:symlink', 'artisan:migrate');

after('deploy:failed', 'deploy:unlock');
after('success', 'artisan:cache:clear');

// Rollback
task('rollback:full', [
    'rollback',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:migrate:rollback',
    'php-fpm:restart',
    'artisan:cache:clear',
]);

task('rollback_tasks', [
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:route:cache-separate',
    'php-fpm:restart',
    'artisan:cache:clear',
]);
after('rollback', 'rollback_tasks');
