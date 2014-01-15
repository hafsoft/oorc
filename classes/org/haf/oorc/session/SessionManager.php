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

namespace org\haf\oorc\session;


use org\haf\oorc\object\Object;

/**
 * Class SessionManager
 *
 * @package org\haf\rcp\session
 * @property string $id
 */
class SessionManager extends Object
{

    /** @var  IHandler */
    private $handler;

    /** @var  ISession */
    private $currentSession = null;

    public function __construct(IHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if ($this->currentSession != null) {
            return $this->currentSession->getId();
        } else {
            return null;
        }
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->assertHandlerDefined();
        $this->currentSession = $this->handler->resume($id);
    }

    public function destroy()
    {
        if ($this->currentSession != null) {
            $this->assertHandlerDefined();
            $this->handler->destroy($this->currentSession);
            $this->currentSession = null;
        }
    }

    /**
     * @param \org\haf\oorc\session\IHandler $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return \org\haf\oorc\session\IHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    public function __get($name)
    {
        return $this->getCurrentSession()->getValue($name);
    }

    public function __set($name, $value)
    {
        $this->getCurrentSession()->setValue($name, $value);
    }

    public function __isset($name)
    {

    }

    public function __unset($name)
    {
        $this->getCurrentSession()->removeValue($name);
    }

    private function &getCurrentSession()
    {
        if ($this->currentSession == null) {
            $this->assertHandlerDefined();
            $this->currentSession = $this->handler->start();
        }

        return $this->currentSession;
    }

    private function assertHandlerDefined()
    {
        if ($this->handler == null) {
            throw new HandlerNotDefinedException();
        }
    }

} 