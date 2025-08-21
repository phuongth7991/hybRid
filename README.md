## Task list

### Core module
- [x] Make core
- [x] Make command generate repository and admin
- [x] Add menu class
- [x] Add form class
- [x] add template crud create
- [x] Add breadcrumb
- [x] Add hook beforeCreate, afterCreate, beforeUpdate, afterUpdate on admin Class
### Form builder
- [x] Image input
- [x] date picker
- [x] date range picker
- [x] Editor
- [x] CKFinder
- [ ] Base role & permission

## Command

```shell
php artisan make:admin User # make new admin class and form
```

### Create admin class with repository

```shell
php artisan make:admin User --repository # make new admin class with repository
```

### Define @property for model

```shell
php artisan ide-helper:models "App\Models\User"
```

## Admin hook on admin class

- *beforeCreate* : call when form submit before form validation
- *afterCreate*: call when form submit and save data to db success 
- *beforeUpdate*: call when form submit before form validation
- *afterUpdate*: call when form submit and save data to db success
- *beforeCommit*: call when form submit before form validation update or create
- *afterCommit*: call when form submit after save data to db success


## Package

1. Enum : https://github.com/BenSampo/laravel-enum
2. Form builder : https://github.com/kristijanhusak/laravel-form-builder
3. Datatable: https://yajrabox.com/docs/laravel-datatables/10.0/html-builder-column-builder
