<?php

use Mockery as m;
use Payum\Core\Reply\ReplyInterface;
use PayumTW\Ips\Action\NotifyAction;
use Payum\Core\Bridge\Spl\ArrayObject;

class NotifyActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_notify_success()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\Notify');
        $gateway = m::spy('Payum\Core\GatewayInterface');
        $api = m::spy('PayumTW\Ips\Api');

        $returnValue = [];

        $details = new ArrayObject($returnValue);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->andReturnUsing(function ($getHttpRequest) use ($returnValue) {
                $getHttpRequest->request = $returnValue;

                return $getHttpRequest;
            });

        $api
            ->shouldReceive('verifyHash')->with($returnValue)->andReturn(true);

        $action = new NotifyAction();
        $action->setGateway($gateway);
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        try {
            $action->execute($request);
        } catch (ReplyInterface $e) {
            $this->assertSame(200, $e->getStatusCode());
            $this->assertSame('1', $e->getContent());
        }

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once();
        $api->shouldHaveReceived('verifyHash')->with($returnValue)->once();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\Sync'))->once();
    }

    public function test_notify_when_checksum_fail()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\Notify');
        $gateway = m::spy('Payum\Core\GatewayInterface');
        $api = m::spy('PayumTW\Ips\Api');

        $returnValue = [];

        $details = new ArrayObject($returnValue);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $gateway
            ->shouldReceive('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->andReturnUsing(function ($getHttpRequest) use ($returnValue) {
                $getHttpRequest->request = $returnValue;

                return $getHttpRequest;
            });

        $api
            ->shouldReceive('verifyHash')->with($returnValue)->andReturn(false);

        $action = new NotifyAction();
        $action->setGateway($gateway);
        $action->setApi($api);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        try {
            $action->execute($request);
        } catch (ReplyInterface $e) {
            $this->assertSame(400, $e->getStatusCode());
            $this->assertSame('Signature verify fail.', $e->getContent());
        }

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('Payum\Core\Request\GetHttpRequest'))->once();
        $api->shouldHaveReceived('verifyHash')->with($returnValue)->once();
    }
}
