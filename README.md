# CLI CCUL [Laravel]

Add your new user via the terminal!

### Requirements

* PHP >= 7.4
* Laravel >= 7

### Using

```bash
php artisan user:create
```

or with params:

```bash
php artisan user:create [name] [email]
```

### Validation rules

* name
  * min 3 characters
* password
  * require at least 8 characters
  * require at least one letter
  * require at least one uppercase and one lowercase letter
  * require at least one number
  * require at least one symbol
