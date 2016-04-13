# HtpasswdManger

A simple GUI to manage the users and passwords in .htpasswd files. Built with [Lumen](https://lumen.laravel.com).

## Installation

```bash
git clone https://github.com/iresults/HtpasswdManager.git;
cd HtpasswdManager;
composer install;
```

Set the path to the .htpasswd file and the names of the admin users in `.env`:

```
PASSWORD_FILE=.htpasswd
ADMIN_USERS=user1,user2
```

## License

Open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT)
