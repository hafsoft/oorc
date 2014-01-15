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

namespace org\haf\oorc\service\consumer;

use org\haf\oorc\base\ServiceConsumer;
use org\haf\oorc\transfer\IRequestSender;
use org\haf\oorc\transfer\Request;

class RemoteMethodInvoker
{

    /** @var  IRequestSender */
    private $requestSender;

    /**
     * @param IRequestSender $requestSender
     */
    public function __construct(IRequestSender $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    /**
     * @param ServiceConsumer $app
     * @param $serviceName
     * @param $methodName
     * @param $arguments
     * @return mixed
     * @throws \org\haf\oorc\base\Exception
     */
    public function invokeRemoteMethod(ServiceConsumer $app, $serviceName, $methodName, $arguments)
    {
        $request = new Request($app);
        $request->setServiceName($serviceName);
        $request->setMethodName($methodName);
        $request->setArguments($arguments);

        $respond = $this->requestSender->sendRequest($request);
        if ($respond->isError()) {
            throw new RemoteException(
                'Remote throw an error when invoking %s.%s()',
                $serviceName, $methodName,
                $respond->getError()
            );
        }
        return $respond->getData();
    }
} 