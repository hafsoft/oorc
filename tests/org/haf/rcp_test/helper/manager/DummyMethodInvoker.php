<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/15/14 1:14 PM
 */

namespace org\haf\rcp_test\helper\manager;


use org\haf\oorc\RpcConsumer;
use org\haf\oorc\service\consumer\RemoteMethodInvoker;
use org\haf\oorc\service\ServiceMethodInvoker;
use org\haf\oorc\service\ServiceNotFoundException;

class DummyMethodInvoker extends RemoteMethodInvoker {

    public function __construct() {

    }

    public function invokeRemoteMethod(RpcConsumer $app, $serviceName, $methodName, $arguments) {
        if ($serviceName != 'test') {
            throw new ServiceNotFoundException($serviceName);
        }

        $service = new Dummy1Service($app, 'test', null);
        $methodInvoker = new ServiceMethodInvoker($service, $methodName);
        return $methodInvoker->invoke($arguments);

    }

} 