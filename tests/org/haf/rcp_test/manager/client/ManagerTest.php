<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 6:17 PM
 */

namespace org\haf\rcp_test\manager\client;


use org\haf\oorc\service\consumer\RemoteMethodInvoker;
use org\haf\rcp_test\helper\manager\DummyMethodInvoker;

class ManagerTest extends \PHPUnit_Framework_TestCase
{

    /** @var \org\haf\rcp_test\helper\manager\Dummy1Service|\org\haf\oorc\service\consumer\Service */
    private $manager;

    public function __construct()
    {
        $app           = $app = new \org\haf\oorc\RpcConsumer(new DummyMethodInvoker());
        $this->manager = new \org\haf\oorc\service\consumer\Service($app, 'test');
    }

    public function testCallMethod()
    {
        $result = $this->manager->getDummy(1, 'Tes');
        $this->assertNotNull($result);
        $this->assertInstanceOf(\org\haf\rcp_test\helper\object\DummyObject::_name(), $result);
    }

    public function testRestrictedMethod()
    {
        $this->setExpectedException(\org\haf\oorc\service\MethodNotAllowedException::_name());
        $result = $this->manager->restrictedMethod();
        $this->assertNull($result);
    }

    public function testNotFound() {
        $this->setExpectedException(\org\haf\oorc\service\MethodNotFoundException::_name());
        $result = $this->manager->notExistMethod();
        $this->assertNull($result);
    }
}

