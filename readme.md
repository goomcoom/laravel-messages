# Laravel Messages

In cases where multiple resources are being operated on and there are different possible outcomes, or we might want to
inform a user of a side effect they might be aware of, we might want to notify the user using one or more messages.
This package provides a fluent interface to categorise messages and add them to JSON response.

## Installation
Install the package using composer.
```shell script
$ composer require goomcoom/laravel-messages
```
The service provider and facade are registered automatically, but you may do so manually by adding them to the app
config.
```php
// config/app.php

[
    'providers' => [
        // ...
        GoomCoom\Messages\MessagesServiceProvider::class,
    ],
    
    'aliases' => [
        // ...
        'Messages' => GoomCoom\Messages\Facades\Messages::class,
    ],
];
```

## Config
To publish the config file you may use the following command
```shell script
$ php artisan vendor:publish --tag=goomcoom-laravel-messages
```
The config file holds the bags that are available for accepting messages which can customised to your preferences.
```php
// config/goomcoom-laravel-messages.php

return [
    /**
     * –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
     * Bags
     * –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
     * These are the bags that messages can be added to.
     */

    'bags' => [
        'error',
        'info',
        'success',
        'warning',
    ],
];

```


## Adding messages to responses
If you would like the messages to the added to the response automatically, you may use the AddMessagesToResponse
middleware. The middleware checks if there are any messages and adds them to the response's meta content.
For information on using middleware please refer to the official [documentation](https://laravel.com/docs/7.x/middleware).

```
// The messages are added to the response's meta object

{
    data: {
        ...
    },
    meta: {
        ...
        messages: {
            error: [
                'Resource 532 was not updated',
            ],
            info: [
                'We did something you might not have expected'
            ]
        }
    }
}
```
The middleware also checks if the response has a message property and appends the message to the
`meta.messages.error` array.

```
// The response message is appended to the error messages array

{
    message: 'Somethig went wrong.',
    meta: {
        messages: {
            error: [
                'Resource 532 was not updated',
                'Something went wrong.'
            ],
        }
    }
}
```

## Usage
### Adding messages
The first argument is the message bag that the messages are meant to be added to. We use the splat operator to
gather messages, so you may add multiple comma-separated messages at once.
```php
    Messages::add('error', 'Cannot do that!', 'Something went wrong.');
    Messages::add('info', 'Something else happened.');

    /*
        {
            ...
            meta: {
                messages: {
                    error: [
                        'Cannot do that!',
                        'Something went wrong.'
                    ],
                    info: [
                        'Something else happened.'
                    ]
                }
            }
        }
    */
```
It's worth noting that messages are not duplicated within a category.

### Getting a bag
The package uses laravel's [MessageBag](https://laravel.com/api/7.x/Illuminate/Support/MessageBag.html) class 
to categorise the messages. You may retrieve a specific bag using the `getBag` method.

```php
Messages::getBag('waring'); // Returns Illuminate/Support/MessageBag with "warning" messages
```

### Getting all messages
You may also retrieve all the messages as an associative array with the messages grouped by their category.

```php
Messages::getAllMessages();

/*
    [
        'error' => [ ... ],
        'info' => [ ... ],
        'success' => [ ... ],
        'warning' => [ ... ],
    ]
*/
```

### Checking if any messages have been added
You may check if any messages have been added by using the `hasAny` method.

```php
Messages::hasAny(); // Returns boolean
```
