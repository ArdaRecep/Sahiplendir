# Sahiplendir

## Requirements
- PHP >= 8.3
- Composer
- MySQL

## Install
###1. Copy the Environment File
###### Run the following command in the terminal to copy .env.example to .env:
```shell
cp .env.example .env
```
### 2. Extract the Vendor Folder
###### Use the following commands to unzip vendor.zip in its directory and then delete the zip file:

```shell
unzip vendor.zip
rm vendor.zip
```

### 3. Run Database Migrations and Seeders
###### Run the following command to create the database tables and seed the sample data:

```shell
php artisan migrate --seed
```

### 4. Generate Application Key
###### Generate a unique application key by running the following command:

```shell
php artisan key:generate
```

## Run
```shell
php artisan serve
```
