<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p><strong>Prepare virtual machine</strong></p>

- composer install
- php vendor/bin/homestead make
- modify Homestead.yaml
- vagrant up
- vagrant ssh
- mv .env.local .env
- yarn

<p><strong>Prepare DB</strong></p>

- php artisan migrate
- php artisan inetstudio:acl:roles:seed
- php artisan inetstudio:acl:users:admin (admin / password)

<p><strong>Build admin panel front</strong></p>

- npm run dev