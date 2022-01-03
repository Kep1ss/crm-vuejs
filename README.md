# Information System Payroll - siPayroll
### Releas version
- v1.0.0

## Build
- Laravel v8.6.6
- NuxtJS
- PostgreSQL
## System requirement
- PHP v7.4
- PostgreSQL v12.x
- Nginx v1.2x
- NodeJS v15.14.0
- NPM v7.7.6 / YARN v1.22.15

## How to install (for development only)
### API Setup
```sh
# clone repository project
git clone https://gitlab.com/anabasoftware/sipayroll

# change working directory
cd api

# install dependency for laravel
composer install

# migrate the database
php artisan migrate

# start server with artisan 
php artisan serve
```
### Client Setup
```sh 
# change working directory
cd client

# install depedency
yarn install

# start server with whot reload localhost:3000
yarn run dev
```
if using NPM, just change `yarn` with `npm`

## Additional requirement
- GIT latest version
- Composer latest version

## Team
- 4yamKemangi
- imasari
- toniwidodo123

## Developed by
[Anak Bangsa Software](https://anabasoftware.com)