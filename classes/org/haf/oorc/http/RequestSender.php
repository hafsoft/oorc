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

namespace org\haf\oorc\http;


use org\haf\oorc\http\connection\IConnectionManager;
use org\haf\oorc\util\ClassStandardization;
use org\haf\oorc\transfer\AbstractRequestSender;

class RequestSender extends AbstractRequestSender
{
    /** @var  string */
    private $serverUrl;

    /** @var  IConnectionManager */
    private $connectionManager;

    public function __construct($url, IConnectionManager $connectionManager = null)
    {
        $this->serverUrl = $url;
        $this->connectionManager;
    }

    /**
     * @param string $serializerClass
     * @param string $serializedRequest
     * @return string
     */
    protected function sendSerializedRequest($serializerClass, $serializedRequest)
    {
        $contentType = call_user_func([ClassStandardization::phpizeClassName($serializerClass), '_name']);

        $connection = $this->connectionManager->open('POST', $this->serverUrl);
        $connection->addHeader('Content-type', $contentType);
        $connection->addHeader('Content-size', strlen($serializedRequest));
        $connection->addHeader('X-Serializer', $serializerClass);
        $connection->addHeader('Connection', 'close');
        $connection->setData($serializedRequest);

        return $connection->sendAndReturnRespond();
    }

}