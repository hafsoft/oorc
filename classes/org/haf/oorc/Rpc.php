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

use org\haf\shared\config\Config;
use org\haf\shared\php\tool\ObjectFactory;

/**
 * Class Rcp
 *
 * @package org\haf\rcp
 *
 * @property string $name Application name
 * @property string $version Protocol version
 */
class Rpc extends object\Object
{

    const VERSION = '1.0';

    /** @var service\IServiceFactory */
    protected $serviceFactory;

    /** @var  Config */
    protected $config;

    /** @var service\IService[] */
    private $services = array();

    /** @var  string Application name */
    protected $name = "haf-rcp";

    /**
     * @param service\IServiceFactory $serviceFactory
     * @param Config $config [optional] configuration
     */
    public function __construct(service\IServiceFactory $serviceFactory = null, Config $config = null)
    {
        $this->config = $config ? : new util\NullConfig();
        $this->serviceFactory = $serviceFactory;
    }

    /**
     * @param string $name
     * @return service\IService
     * @throws service\ServiceNotFoundException
     */
    public function &getService($name)
    {
        // security check
        // todo: sanitize getManager's name

        if (!isset($this->services[$name])) {
            if ($this->serviceFactory == null) {
                throw new service\FactoryNotDefinedException();
            }
            $this->services[$name] = $this->serviceFactory->buildService(
                $this,
                $name,
                $this->config->get('service.' . $name)
            );
        }
        return $this->services[$name];
    }

    public function getVersion()
    {
        return static::VERSION;
    }

    public function getName()
    {
        return $this->name;
    }
}