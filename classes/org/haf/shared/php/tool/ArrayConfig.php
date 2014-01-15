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

namespace org\haf\shared\php\tool;


use org\haf\shared\config\Config;

class ArrayConfig extends Config
{

    private $data;

    /**
     * @param array|string $input array or file name
     */
    public function __construct($input)
    {
        $data = array();
        if (is_string($input) && is_file($input)) {
            $data = include $input;
        } elseif (is_array($input)) {
            $data = $input;
        }

        $this->data =& $data;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string|int|Config|null
     */
    function getChild($name, $default = null)
    {
        if (is_array($this->data) && isset($this->data[$name])) {
            $out =& $this->data[$name];
        } else {
            $out = $default;
        }
        return new ArrayConfig($out);
    }

    /**
     * @return string
     */
    function stringValue()
    {
        return (string)$this->data;
    }

}