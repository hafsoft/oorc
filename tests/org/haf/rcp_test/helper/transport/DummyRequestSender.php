<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 6:20 PM
 */

namespace org\haf\rcp_test\helper\transport;


use org\haf\oorc\Exception;
use org\haf\oorc\service\ServiceNotFoundException;
use org\haf\oorc\service\MethodNotAllowedException;
use org\haf\oorc\service\MethodNotFoundException;
use org\haf\oorc\Rpc;
use org\haf\oorc\transfer\IRequestSender;
use org\haf\oorc\transfer\Request;
use org\haf\oorc\transfer\Respond;
use org\haf\rcp_test\helper\manager\Dummy1Service;

class DummyRequestSender implements IRequestSender {

    private $app;

    public function __construct() {
        // $this->app = $app;
    }

    /**
     * @param Request $request
     * @return Respond
     */
    public function sendRequest(Request $request)
    {
        $respond = new Respond($this->app, $request);

        try {
            if ($request->getServiceName() != 'test') {
                throw new ServiceNotFoundException($request->getServiceName());
            }

            $manager = new Dummy1Service($this->app, 'test', null);
            if (! $manager->isRemoteAllowed($request->getMethodName())) {
                throw new MethodNotAllowedException();
            }

            $method = array(&$manager, 'getDummy');
            if (! is_callable($method)) {
                throw new MethodNotFoundException();
            }

            $data = call_user_func($method, $request->getArguments());
            $respond->setData($data);
        }

        catch (Exception $e) {
            $respond->setError($e);
        }

        return $respond;
    }
}