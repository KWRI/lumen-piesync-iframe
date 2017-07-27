# Lumen piesync Iframe

This package contain piesync partner helpers.


## Pem file generator
Piesync partner require you to have 2 PEM files.
1 for development and 1 for production environment.

You can generate PEM file with:
To generate token you can simply run this on your terminal
```
$ ./vendor/bin/gen_piesync_pem
```

For generate development token:
```
$ ./vendor/bin/gen_piesync_pem --dev
```
Your token will be on `YOUR_PROJECT_DIR/private/piesync`

## Token Builder:
To build token put this on your script:

```php
<?php

$payload->setPartner('kw')
    ->setApp('googlecontacts')
    ->setTeamId('your-team-id')
    ->setUserId('your-user-id')
    ->setEmail('your-email')
    ->setApiAuth('your-api-auth')
    ->setExpiration(strtotime('+3 hours'));

$token = $tokenGenerator->setPayload($payload)
    ->setPrivateKeyFile($this->app->basePath('private/piesync/partner_private.pem'))
    ->setPiesyncPublicKeyFile($this->app->basePath('private/piesync/piesync_public.pem'))
    ->build();
```
