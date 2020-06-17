# Laravel Messages

Sometimes you may wish to return responses with some extra messages informing the user of what happened. This package
provides a fluent interface to add messages from anywhere within in your code base and gracefully add them to your JSON
response. The messages are comparable to [session flash messages](https://laravel.com/docs/7.x/session#flash-data)
but do not rely on the session and are especially useful for APIs.

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
The config file holds the bags that are available for accepting messages which are fully customizable.
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
If you would like the messages to the added to the response automatically, you may use the `AddMessagesToResponse`
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
// Returns Illuminate/Support/MessageBag with "warning" messages
Messages::getBag('warning');
```

### Getting all messages
You may also retrieve all the messages as an associative array with the messages grouped by their category by using the
`getAll` method.

```php
Messages::getAll();

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
// Returns boolean
Messages::hasAny();
```

### Removing messages
You may remove messages from a specific bag by using the `remove` method.

```php
// Removes all messages from the success bag.
Messages::remove('success', '*');

// Removes the "To be removed" & "Also to be removed" messages from the error bag'
Messages::remove('error', 'To be removed', 'Also to be removed');
```

### Resetting all message bags
You may reset all message bags by calling the `reset` method.

```php
// Removes all messages
Messages::reset();
```
