# Esunbank

The Payum extension to rapidly build new extensions.

1. Create new project

```bash
$ composer create-project payum-tw/allpay
```

2. Replace all occurrences of `payum` with your vendor name. It may be your github name, for now let's say you choose: `acme`.
3. Replace all occurrences of `allpay` with a payment gateway name. For example Stripe, Paypal etc. For now let's say you choose: `allpay`.
4. Register a gateway factory to the payum's builder and create a gateway:

```php
<?php

use Payum\Core\PayumBuilder;
use Payum\Core\GatewayFactoryInterface;

$defaultConfig = [];

$payum = (new PayumBuilder)
    ->addGatewayFactory('allpay', function(array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new \PayumTW\Allpay\AllpayGatewayFactory($config, $coreGatewayFactory);
    })

    ->addGateway('allpay', [
        'factory'     => 'allpay',
        'MerchantID'  => '2000132',
        'HashKey'     => '5294y06JbISpM5x9',
        'HashIV'      => 'v77hoKGq4kWxNNIS',
        'sandbox'     => true,
    ])

    ->getPayum()
;
```

5. While using the gateway implement all method where you get `Not implemented` exception:

```php
<?php

use Payum\Core\Request\Capture;

$allpay = $payum->getGateway('allpay');

$model = new \ArrayObject([
  // ...
]);

$allpay->execute(new Capture($model));
```

## Resources

* [Documentation](https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/index.md)
* [Questions](http://stackoverflow.com/questions/tagged/payum)
* [Issue Tracker](https://github.com/Payum/Payum/issues)
* [Twitter](https://twitter.com/payumphp)

## License

Skeleton is released under the [MIT License](LICENSE).
