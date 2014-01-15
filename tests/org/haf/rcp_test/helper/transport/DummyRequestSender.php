<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 6:20 PM
 */

namespace org\haf\rcp_test\helper\transport;


use org\haf\oorc\base\Exception;
use org\haf\oorc\service\ServiceMethodInvoker;
use org\haf\oorc\service\ServiceNotFoundException;
use org\haf\oorc\transfer\IRequestSender;
use org\haf\oorc\transfer\Request;
use org\haf\oorc\transfer\Respond;
use org\haf\rcp_test\helper\service\Dummy1Service;

class DummyRequestSender implements IRequestSender {

    public function __construct() {
        // $this->app = $app;
    }

    /**
     * @param Request $request
     * @return Respond
     */
    public function sendRequest(Request $request)
    {
        $respond = new Respond();

        try {
            if ($request->getServiceName() != Dummy1Service::_name(true)) {
                throw new ServiceNotFoundException($request->getServiceName());
            }

            $service = new Dummy1Service();
            $methodInvoker = new ServiceMethodInvoker($service, $request->getMethodName());
            $data = $methodInvoker->invoke($request->getArguments());
            $respond->setData($data);
        }

        catch (Exception $e) {
            $respond->setError($e);
        }

        return $respond;
    }
}