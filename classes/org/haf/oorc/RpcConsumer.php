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


use org\haf\oorc\service\consumer\ServiceFactory;
use org\haf\shared\config\Config;

class RpcConsumer extends Rpc
{

    /** @var  transfer\IRequestSender */
    private $requestSender;

    /** @var  string */
    private $sessionId;

    /**
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        parent::__construct(new ServiceFactory(), $config);
    }

    /**
     * @param string $serviceName
     * @param string $methodName
     * @param array $arguments
     * @throws service\consumer\RemoteException
     * @return mixed
     */
    public function callServiceMethod($serviceName, $methodName, array $arguments = null)
    {
        $request = new transfer\Request();
        $request->setServiceName($serviceName);
        $request->setMethodName($methodName);
        $request->setArguments($arguments);
        $request->setVersion($this->getVersion());
        if ($sessionId = $this->getSessionId()) {
            $request->getExtraData()->set('sessionId', $sessionId);
        }

        $respond = $this->requestSender->sendRequest($request);
        if ($respond->isError()) {
            throw new service\consumer\RemoteException(
                'Remote throw an error when invoking %s.%s()',
                $serviceName, $methodName,
                $respond->getError()
            );
        }

        if ($sessionId = $respond->getExtraData()->get('sessionId')) {
            $this->setSessionId($sessionId);
        }
        return $respond->getData();
    }

    /**
     * @return string
     */
    protected function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    protected function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }
} 