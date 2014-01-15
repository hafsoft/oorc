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

namespace org\haf\oorc\util;


use org\haf\oorc\object\SerializableObject;
use org\haf\oorc\serializer\IArraiable;
use org\haf\oorc\serializer\ISerializable;

class Dict extends SerializableObject implements ISerializable, IArraiable
{

    /** @var array */
    protected $data;

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    public function get($name, $default = null)
    {
        return (substr_compare($name, '__', 0, 2) != 0 && isset($this->data[$name])) ? $this->data[$name] : $default;
    }

    public function set($name, $value)
    {
        if (substr_compare($name, '__', 0, 2) != 0) {
            $this->data[$name] = $value;
        }
    }

    public function remove($name)
    {
        if (substr_compare($name, '__', 0, 2) != 0) {
            unset($this->data[$name]);
        }
    }

}