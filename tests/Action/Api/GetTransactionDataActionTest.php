<?php

use Mockery as m;
use PayumTW\Ips\Action\Api\GetTransactionDataAction;
use PayumTW\Ips\Api;
use PayumTW\Ips\Request\Api\GetTransactionData;
use Payum\Core\Bridge\Spl\ArrayObject;

class GetTransactionDataActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_get_transaction_data()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $api = m::mock(Api::class);
        $request = m::mock(GetTransactionData::class);
        $details = new ArrayObject();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request->shouldReceive('getModel')->twice()->andReturn($details);

        $api->shouldReceive('getTransactionData')->once()->andReturn($details);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $action = new GetTransactionDataAction();
        $action->setApi($api);
        $action->execute($request);
    }
}
