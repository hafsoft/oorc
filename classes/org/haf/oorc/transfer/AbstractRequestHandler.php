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

namespace org\haf\oorc\transfer;

use org\haf\oorc\RpcProvider;
use org\haf\oorc\serializer\ISerializer;
use org\haf\shared\php\tool\ObjectFactory;

abstract class AbstractRequestHandler implements IRequestHandler
{

    /** @var ISerializer[] */
    private $serializerCache = array();

    /**
     * @return Request
     */
    public function receiveRequest()
    {
        $serializer        = $this->getSerializer();
        $serializedRequest = $this->getRequestString();
        return $serializer->unserialize($serializedRequest);
    }

    public function sendRespond(Respond $respond)
    {
        $serializer        = $this->getSerializer();
        $serializedRespond = $serializer->serialize($respond);
        $this->sendRespondString($serializedRespond);
    }

    /**
     * @throws SerializerNotSupportedException
     * @return ISerializer
     */
    protected function &getSerializer()
    {
        $className = $this->getSerializerClass();
        if (!isset($this->serializerCache[$className])) {
            if (class_exists($className) && is_subclass_of($className, 'org\haf\oorp\object\ISerializer')) {
                $this->serializerCache[$className] = ObjectFactory::constructObject($className);
            } else {
                throw new SerializerNotSupportedException('Serializer %s is not supported', $className);
            }
        }
        return $this->serializerCache[$className];
    }

    /**
     * @return string serializer class name
     */
    abstract protected function getSerializerClass();

    /**
     * @return string
     */
    abstract protected function getRequestString();

    /**
     * @param string $respondString
     */
    abstract protected function sendRespondString($respondString);

}