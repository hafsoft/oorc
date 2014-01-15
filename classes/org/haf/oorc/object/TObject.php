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

namespace org\haf\oorc\object;


use org\haf\oorc\util\ClassStandardization;

trait TObject
{

    /**
     * @param bool $standardize
     * @return string
     */
    public function className($standardize = false)
    {
        if ($standardize) {
            return ClassStandardization::standardizeClassName(get_class($this));
        } else {
            return get_class($this);
        }
    }

    /**
     * @param bool $standardize
     * @return string
     */
    public static function _name($standardize = false)
    {
        if ($standardize) {
            return ClassStandardization::standardizeClassName(get_called_class());
        } else {
            return get_called_class();
        }
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else {
            throw new PropertyNotFoundException('Property %s not found in class %s', $name, get_class($this));
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new PropertyIsReadOnlyException('Property %s in class %s is read only', $name, get_class($this));
        } else {
            throw new PropertyNotFoundException('Property %s not found in class %s', $name, get_class($this));
        }
    }


} 