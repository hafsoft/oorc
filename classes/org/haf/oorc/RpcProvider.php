<?php
/**
 * HafSoft Object Oriented Remote Call - PHP Implementation
 * Copyright (c) 2014 Abi Hafshin <abiehafshin@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace org\haf\oorc;


use org\haf\oorc\service\ServiceMethodInvoker;
use org\haf\oorc\transfer\IRequestHandler;
use org\haf\oorc\transfer\Request;
use org\haf\oorc\transfer\Respond;

class RpcProvider extends Rpc
{

    /** @var  session\SessionManager */
    protected $session;

    /**
     * @param IRequestHandler $requestHandler
     * @return Respond
     */
    public function handleRequest(IRequestHandler $requestHandler)
    {
        $request = $requestHandler->receiveRequest();
        $respond = $this->processRequest($request);
        $requestHandler->sendRespond($respond);
    }

    /**
     * @param Request $request
     * @return \org\haf\oorc\transfer\Respond
     */
    public function processRequest(Request $request)
    {
        $respond = new Respond($this);
        try {
            if ($this->isVersionSupported($request->getVersion())) {
                throw new VersionNotSupportedException();
            }

            if ($sessionId = $request->getExtraData()->get('sessionId')) {
                $this->getSession()->setId($sessionId);
            }

            $returnValue = $this->executeCommand(
                $request->getServiceName(),
                $request->getMethodName(),
                $request->getArguments()
            );

            $respond->setData($returnValue);
            if ($this->session && $this->session->getId()) {
                $request->getExtraData()->set('sessionId', $this->session->getId());
            }

        } catch (Exception $e) {
            $respond->setError($e);
        } catch (\Exception $e) {
            $respond->setError(new Exception('Unknown Exception', 0, $e));
        }
        return $respond;
    }

    /**
     * @param $serviceName
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    protected function executeCommand($serviceName, $methodName, $arguments)
    {
        $service = $this->getService($serviceName);
        $method  = new ServiceMethodInvoker($service, $methodName);
        return $method->invoke($arguments);
    }

    protected function isVersionSupported($version)
    {
        return $version == $this->getVersion();
    }


    public function getSession()
    {
        if ($this->session = null) {
            $sessionConfig = $this->config->get('cache');
            $handlerClass  = $sessionConfig->get('class', 'org\haf\rcp\session\php\Handler');
            // todo: check if handlerClass is subclass of session\IHandler
            /** @var session\IHandler $sessionHandler */
            $sessionHandler = ObjectFactory::constructObject($handlerClass, array(&$this, &$sessionConfig));
            $this->session  = new session\SessionManager($sessionHandler);
        }

        return $this->session;
    }

}