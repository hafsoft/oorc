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


use org\haf\oorc\serializer\IArraiable;
use org\haf\shared\php\tool\ObjectFactory;

class SerializableObject extends Object implements IArraiable, IObject
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            '__construct' => $this->_getConstructParam(),
            '__prop'      => $this->_getProperties(),
        );
    }

    /**
     * @param array $array
     * @return \org\haf\oorc\serializer\ISerializable
     */
    public static function fromArray($array)
    {
        /** @var SerializableObject $object */
        $object = ObjectFactory::constructObject(static::_name(), $array['__construct']);
        $object->_setProperties($array['__prop']);
        return $object;
    }

    /**
     * @return array
     */
    protected function _getProperties()
    {
        $propertiesName = $this->_getPropertiesName();
        if ($propertiesName === true) {
            return get_object_vars($this);
        } elseif (is_array($propertiesName)) {
            $result = array();
            foreach ($propertiesName as $prop) {
                $result[$prop] = $this->$prop;
            }
            return $result;
        } else {
            return null;
        }
    }

    protected function _getPropertiesName()
    {
        return true;
    }

    protected function _setProperties($properties)
    {
        foreach ($properties as $prop => $value) {
            $this->$prop = $value;
        }
    }

    /**
     * @return null|array
     */
    protected function _getConstructParam()
    {
        return null;
    }
}