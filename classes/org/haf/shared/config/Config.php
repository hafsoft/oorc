<?php
/**
 * HafSoft Shared Library
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

namespace org\haf\shared\config;


abstract class Config
{

    /**
     * @param string $name
     * @param mixed $default
     * @return Config|null
     */
    abstract protected function getChild($name, $default = null);

    public function get($name, $default = null)
    {
        $arr = explode('.', $name, 1);
        if (count($arr) > 1) {
            return $this->getChild($arr[0])->get($arr[1], $default);
        } else {
            return $this->getChild($arr[0], $default);
        }
    }

    /**
     * @return string
     */
    abstract function stringValue();

    public function __get($name)
    {
        return $this->getChild($name);
    }

    public function __toString()
    {
        return $this->stringValue();
    }
}