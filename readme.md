# Laravel Messages

In cases where multiple resources are being operated on and there are different possible outcomes, or we might want to
inform a user of a side effect they might be aware of, we might want to notify the user using one or more messages.
There are 2 main ways of returning feedback to a user, Notifications and manually adding messages to the response.
Notifications work great for asynchronous jobs and events but is overkill for returning simple feedback to users. This
package provides a fluent interface to categorise messages and add them to the response.

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


## Middleware
If you would like the messages to the added to the response automatically, you may use the package's
AddMessagesToResponse middleware. The middleware checks if there are any messages and adds them to the response's
meta content. For information on using middleware please refer to the official
[documentation](https://laravel.com/docs/7.x/middleware).

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
        Resulting response

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


