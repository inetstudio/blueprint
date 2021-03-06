<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p><strong>Prepare virtual machine</strong></p>

- cp .env.local .env
- docker-compose up -d
- docker-compose exec php composer install
- docker-compose exec php php artisan key:generate
- docker-compose exec php php artisan storage:link
- docker-compose run --rm node yarn install

<p><strong>Prepare DB</strong></p>

- docker-compose exec php php artisan migrate
- docker-compose exec php php artisan inetstudio:acl:roles:seed
- docker-compose exec php php artisan inetstudio:acl:users:admin (admin / password)

<p><strong>Build admin panel front</strong></p>

- docker-compose run --rm node npm run dev


<p><strong>Console Aliases</strong></p>

- alias dcphp="docker-compose exec php php"
- alias dcphpa="docker-compose exec php php artisan"
- alias dcphpc="docker-compose exec php composer"
- alias dcphp_exd="docker-compose exec php sh -l -c enable-xdebug"
- alias dcphp_dxd="docker-compose exec php sh -l -c disable-xdebug"
- alias dcnode="docker-compose run --rm node"
