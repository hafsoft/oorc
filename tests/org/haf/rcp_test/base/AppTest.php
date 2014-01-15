<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/12/14 8:23 PM
 */
namespace org\haf\rcp_test;


class AppTest extends \PHPUnit_Framework_TestCase {

    public function testGetManager()
    {
        $managerFactory = new \org\haf\rcp_test\helper\service\DummyServiceFactory();
        $rcp = new \org\haf\oorc\base\App($managerFactory);

        $manager1 = $rcp->getService('dummy1');
        $this->assertNotNull($manager1);
        $this->assertInstanceOf(\org\haf\rcp_test\helper\service\Dummy1Service::_name(), $manager1);

        $this->setExpectedException(\org\haf\oorc\service\ServiceNotFoundException::_name());
        $manager2 = $rcp->getService('fake');
        $this->assertNull($manager2);
    }

    public function testGetVersion() {
        $rcp = new \org\haf\oorc\base\App(new \org\haf\rcp_test\helper\service\DummyServiceFactory());
        $this->assertSame(\org\haf\oorc\base\App::VERSION, $rcp->getVersion());
    }
}
 