<?php
namespace Deployer;

require 'app/Utils/Deployer/recipes/sentry.php';
require 'vendor/deployer/deployer/recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/slack.php';
require 'vendor/deployer/recipes/recipe/yarn.php';

// Project name
set('application', '');

// Project repository
set('repository', 'git@bitbucket.org:');
set('branch', 'master');
set('git_tty', true); // [Optional] Allocate tty for git on first deployment

set('sentry_repository_name', '');
set('sentry', [
    'environment' => get('target'),
    'organization' => 'inetstudio',
    'projects' => [
        'skin-ru-backend',
    ],
    'token' => '',
    'version' => trim(exec('git --git-dir ' . realpath('.git') . ' describe --tags')).'-PROJECT_NAME',
    'url' => 'https://bitbucket.org/',
]);

set('slack_webhook', '');
set('slack_title', '');
set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');
set('slack_success_text', 'Deploy to *{{target}}* (_{{release_path}}_) successful');
set('slack_failure_text', 'Deploy to *{{target}}* (_{{release_path}}_) failed');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', [
    'storage/app/public/pages',
    'storage/app/public/temp',
    'storage/app/public/users',
    'storage/uploads',
    'vendor',
]);

// Hosts
set('default_stage', 'test');
host('')
    ->stage('test')
    ->port(22)
    ->user('')
    ->identityFile('')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('deploy_path', '');

host('')
    ->stage('production')
    ->port(22)
    ->user('')
    ->identityFile('')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('deploy_path', '');

// Tasks
before('deploy', 'slack:notify');

after('deploy:vendors', 'yarn:install');

desc('Prepare environment');
task('files:permissions:artisan', function () {
    run("cd {{release_path}} && chmod +x artisan");
});
after('deploy:shared', 'files:permissions:artisan');

task('files:test_environment', function () {
    run('mv {{release_path}}/.env.test {{release_path}}/.env');
    run('mv {{release_path}}/public/robots.txt.test {{release_path}}/public/robots.txt');
    run('rm {{release_path}}/.env.production');
    run('rm {{release_path}}/public/robots.txt.production');
})->onStage('test');
after('deploy:shared', 'files:test_environment');

task('build:test:admin_assets', function () {
    run('cd {{release_path}} && npm run dev');
})->onStage('test');
after('yarn:install', 'build:test:admin_assets');

task('files:prod_environment', function () {
    run('mv {{release_path}}/.env.production {{release_path}}/.env');
    run('mv {{release_path}}/public/robots.txt.production {{release_path}}/public/robots.txt');
    run('rm {{release_path}}/.env.test');
    run('rm {{release_path}}/public/robots.txt.test');
})->onStage('production');
after('deploy:shared', 'files:prod_environment');

task('build:prod:admin_assets', function () {
    run('cd {{release_path}} && npm run production');
})->onStage('production');
after('yarn:install', 'build:prod:admin_assets');

desc('Execute artisan route:cache-separate');
task('artisan:route:cache-separate', function () {
    run('{{bin/php}} {{release_path}}/artisan route:cache-separate');
});
after('artisan:config:cache', 'artisan:route:cache-separate');

desc('Restart PHP-FPM service');
task('php-fpm:restart:production', function () {
    run('sudo systemctl restart php7.2-fpm.service');
})->onStage('production');
after('deploy:symlink', 'php-fpm:restart:production');

task('php-fpm:restart:test', function () {
    run('sudo systemctl restart php7.1-fpm.service');
})->onStage('test');
after('deploy:symlink', 'php-fpm:restart:test');

before('deploy:symlink', 'deploy:public_disk');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('success', 'deploy:sentry');
after('success', 'slack:notify:success');
after('deploy:failed', 'slack:notify:failure');
