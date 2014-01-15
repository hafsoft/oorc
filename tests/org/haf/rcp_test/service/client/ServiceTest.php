<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 6:17 PM
 */

namespace org\haf\rcp_test\service\client;

use org\haf\rcp_test\helper\service\Dummy1Service;
use org\haf\rcp_test\helper\transport\DummyRequestSender;

class ServiceTest extends \PHPUnit_Framework_TestCase
{

    /** @var \org\haf\rcp_test\helper\service\Dummy1Service */
    private $service;

    public function __construct()
    {
        $app           = $app = new \org\haf\oorc\base\ServiceConsumer(new DummyRequestSender());
        $this->service = new \org\haf\oorc\service\consumer\Service($app, Dummy1Service::_name(true));
    }

    public function testCallMethod()
    {
        $result = $this->service->getDummy(1, 'Tes');
        $this->assertNotNull($result);
        $this->assertInstanceOf(\org\haf\rcp_test\helper\object\DummyObject::_name(), $result);
    }

    public function testRestrictedMethod()
    {
        $this->setExpectedException(\org\haf\oorc\service\MethodNotAllowedException::_name());
        $result = $this->service->restrictedMethod();
        $this->assertNull($result); // this assert is


    }

    public function testNotFound() {
        $this->setExpectedException(\org\haf\oorc\service\MethodNotFoundException::_name());
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->service->notExistMethod();
        $this->assertNull($result);
    }
}

