<?php

use Mockery as m;
use PayumTW\Ips\IpsGatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;

class IpsGatewayFactoryTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_create_factory()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $httpClient = m::mock('Payum\Core\HttpClientInterface');
        $message = m::mock('Http\Message\MessageFactory');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $gateway = new IpsGatewayFactory();
        $config = $gateway->createConfig([
            'payum.api' => false,
            'MerCode' => md5(rand()),
            'MerKey' => md5(rand()),
            'Account' => md5(rand()),
            'payum.required_options' => [],
            'payum.http_client' => $httpClient,
            'httplug.message_factory' => $message,
        ]);

        $api = call_user_func($config['payum.api'], ArrayObject::ensureArrayObject($config));
        $this->assertInstanceOf('PayumTW\Ips\Api', $api);
    }
}
