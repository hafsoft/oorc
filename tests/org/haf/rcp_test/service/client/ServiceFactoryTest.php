<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/14/14 2:19 PM
 */

namespace org\haf\rcp_test\service\client;


use org\haf\oorc\base\ServiceConsumer;
use org\haf\oorc\service\consumer\Service;
use org\haf\oorc\service\consumer\ServiceFactory;
use org\haf\rcp_test\helper\transport\DummyRequestSender;

class ServiceFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testCreateManager() {
        $managerFactory = new ServiceFactory(new DummyRequestSender());
        $app = new ServiceConsumer(new DummyRequestSender());
        /** @var Service $manager */
        $manager = $managerFactory->buildService($app, 'test');

        $this->assertNotNull($manager);
        $this->assertInstanceOf(Service::_name(), $manager);
    }
}
 