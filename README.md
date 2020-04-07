

# Ajutam - Aplicatie management voluntari


## Getting started

### Installing
#### Manual

##### Install the LAMP Packages 

> The full documentation can be found here: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04

##### Install the PHP prerequisite modules (if not installed)

```bash

# php
sudo apt install php-cli composer

# php extensions
sudo apt-get install php7.2-mbstring php7.2-simplexml php7.2-gd  php7.2-zip

```

##### Install nodejs (if not yet installed)

```bash

sudo apt install nodejs npm

```

##### Create the MYSQL database and user 

Connect to the MYSQL server with your favorite tool and run: 

```sql

CREATE DATABASE laravel;

GRANT ALL PRIVILEGES ON *.* TO 'Some username here'@'localhost' IDENTIFIED BY 'Your Password here'; 

```

##### Install and configure NGROK (only necesarry if you wanna skip using a self signed cert)

https://dashboard.ngrok.com/get-started and follow the steps listed here. 

##### Register a Facebook application for testing 
- make a note of the appid and appsecret 

##### Clone the gibhub repo with the solution to your machine

```bash

git clone https://github.com/gabibora/ajutam.git

cd ajutam

```
##### Provision dependencies

```bash
composer install
```

##### Create the .env file 

Create a copy of the .env.sample file and call it .env 
Update the following entries to match your env: 

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[dbname]
DB_USERNAME=[dbuser]
DB_PASSWORD=[dbpassword]

```
##### Run artisan to provision stuff

```bash
php artisan key:generate

php artisan migrate --seed

php artisan passport:install

```

##### Install global JS packages

```bash
sudo npm install --global cross-env
```

##### Install the local JS packages 
```bash
npm install
```
##### Build the app 
```
npm run dev 
```
##### Test that everything is running 
```
php artisan serve 
```
In a different tab:
```
./ngrok http 8000
```
Browse to https://localhost:8000 and you should see the application

Browse to the URL indicated by ngrok and you should see the application and be able to login. 

##### Set the admin role for your test user 

Connect to the MYSQL server with your favorite tool and run: 
```sql
select * from users; 
```
make a not of the ID of the user you want to set as admin 
```sql
UPDATE laravel.model_has_roles set role_id = 1 where model_id = [substitute with your user id];
```
#### Docker
```sh
docker-compose up -d
```
Run database migration and seed within Docker container
```sh
# Get laravel docker container ID from containers list
docker ps

docker exec -it <container ID> php artisan migrate --seed 
# Where <container ID> is the "laravel" container name, ex: docker_laravel_1
```

`npm` commands are also supported:
```sh
docker exec -it <container ID> npm run watch 
# Where <container ID> is the "laravel" container name, ex: docker_laravel_1
...
```

## Running the tests
* Tests system is under development

## Deployment and/or CI/CD
This project uses [Envoy](https://laravel.com/docs/5.8/envoy) for deployment, and [GitLab CI/CD](https://about.gitlab.com/product/continuous-integration/). Please check `Envoy.blade.php` and `.gitlab-ci.yml` for more detail.

## Built with
* [Laravel](https://laravel.com/) - The PHP Framework For Web Artisans
* [VueJS](https://vuejs.org/) - The Progressive JavaScript Framework
* [Element](https://element.eleme.io/) - A  Vue 2.0 based component library for developers, designers and product managers
* [Vue Admin Template](https://github.com/PanJiaChen/vue-admin-template) - A minimal vue admin template with Element UI


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details.

