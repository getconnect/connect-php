# Connect PHP SDK

[![Join the chat at https://gitter.im/getconnect/connect-php](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/getconnect/connect-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/getconnect/connect-php.svg)](https://travis-ci.org/getconnect/connect-php)
[![Latest Stable Version](https://poser.pugx.org/connect/connect-client/version)](https://packagist.org/packages/connect/connect-client)

The Connect PHP SDK allows you to push events to Connect from PHP.

If you don’t already have a Connect account, [sign up here](https://getconnect.io) - it’s free!

## Installation
The easiest way to install the Connect SDK is to use [Composer](https://getcomposer.org/).

Add `connect/connect-client` as a dependency and run composer update.
```
"require": {
    …
    "connect/connect-client" : "0.*"
    …
}
```

## Usage

### Initializing a client

```php
use Connect\Connect;

Connect::initialize('your-project-id', 'your-push-api-key');
```

### Pushing events

Once you have initialized Connect, you can push events easily:

```php
$purchase = [
	'customer' => [
	   'firstName' => 'Tom',
	   'lastName' => 'Smith'
	],
	'product' => '12 red roses',
	'purchasePrice' => 34.95
];

Connect::push('purchases', $purchase);
```

You can also push events in batches:
```php
$batch = [
	'purchases' => [
		[
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith'
			],
			'product' => '12 red roses',
			'purchasePrice' => 34.95
		],
		[
            'customer' => [
                'firstName' => 'Fred',
				'lastName' => 'Jones'
			],
			'product' => '12 pink roses',
			'purchasePrice' => 38.95
		]
	]
];

Connect::push($batch);
```

### Generating filtered keys

To generate a filtered key.
```php
$masterKey = 'YOUR_MASTER_KEY';
$keyDefinition = [
		'filters' => [
			'type' => 'cycling'
		],
		"canQuery" => True,
		"canPush" => True
	];
	
$filteredKey = Connect::generateFilteredKey($keyDefinition, $masterKey);
print $filtered_key
``` 

### Exception handling

When pushing events, exceptions could be thrown, so you should either ignore or handle those exceptions gracefully.

Currently, the following exception could be thrown when pushing events:

* `InvalidEventException` - the event being pushed is invalid (e.g. invalid event properties)

## License

The SDK is released under the MIT license.

## Contributing

We love open source and welcome pull requests and issues from the community!
