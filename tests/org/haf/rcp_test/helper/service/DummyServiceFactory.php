<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/12/14 9:07 PM
 */

namespace org\haf\rcp_test\helper\service;

use org\haf\oorc\service\ServiceNotFoundException;

class DummyServiceFactory implements \org\haf\oorc\service\IServiceFactory {

    /**
     * @param \org\haf\oorc\Rpc $app
     * @param string $name
     * @param \org\haf\shared\config\Config|null $config
     * @throws \org\haf\oorc\service\ServiceNotFoundException
     * @return \org\haf\oorc\service\IService
     */
    public function buildService(\org\haf\oorc\Rpc $app, $name, \org\haf\shared\config\Config $config = null)
    {
        if ($name == 'dummy1') {
            return new Dummy1Service($app, $name, $config);
        }
        else {
            throw new ServiceNotFoundException($app, $name);
        }
    }
}