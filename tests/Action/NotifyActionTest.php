<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayInterface;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use PayumTW\Ips\Action\NotifyAction;
use PayumTW\Ips\Api;

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
        | Set
        |------------------------------------------------------------
        */

        $action = new NotifyAction();
        $gateway = m::mock(GatewayInterface::class);
        $request = m::mock(Notify::class);
        $api = m::mock(Api::class);
        $model = new ArrayObject([
            'MerBillNo' => 'foo',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with(GetHttpRequest::class)->once();

        $api
            ->shouldReceive('parseResult')->once()->andReturn([
                'MerBillNo' => 'foo',
            ])
            ->shouldReceive('verifyHash')->once()->andReturn(true);

        $request->shouldReceive('getModel')->twice()->andReturn($model);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
            $this->assertSame('1', $response->getContent());
            $this->assertSame(200, $response->getStatusCode());
        }
    }

    public function test_notify_vaild_fail()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new NotifyAction();
        $gateway = m::mock(GatewayInterface::class);
        $request = m::mock(Notify::class);
        $api = m::mock(Api::class);
        $model = new ArrayObject([
            'MerBillNo' => 'foo',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with(GetHttpRequest::class)->once();

        $api
            ->shouldReceive('parseResult')->once()->andReturn([
                'MerBillNo' => 'foo',
            ])
            ->shouldReceive('verifyHash')->once()->andReturn(false);

        $request->shouldReceive('getModel')->twice()->andReturn($model);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
            $this->assertSame('Signature verify fail.', $response->getContent());
            $this->assertSame(400, $response->getStatusCode());
        }
    }

    public function test_notify_mer_mbill_no_fail()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new NotifyAction();
        $gateway = m::mock(GatewayInterface::class);
        $request = m::mock(Notify::class);
        $api = m::mock(Api::class);
        $model = new ArrayObject([
            'MerBillNo' => 'foo',
        ]);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $gateway->shouldReceive('execute')->with(GetHttpRequest::class)->once();

        $api
            ->shouldReceive('parseResult')->once()->andReturn([
                'MerBillNo' => 'bar',
            ])
            ->shouldReceive('verifyHash')->once()->andReturn(true);

        $request->shouldReceive('getModel')->twice()->andReturn($model);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action->setGateway($gateway);
        $action->setApi($api);
        try {
            $action->execute($request);
        } catch (HttpResponse $response) {
            $this->assertSame('MerBillNo fail.', $response->getContent());
            $this->assertSame(400, $response->getStatusCode());
        }
    }
}
