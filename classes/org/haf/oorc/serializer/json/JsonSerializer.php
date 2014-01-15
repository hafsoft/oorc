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

namespace org\haf\oorc\serializer\json;


use org\haf\oorc\object\Object;
use org\haf\oorc\serializer\IArraiable;
use org\haf\oorc\serializer\ISerializable;
use org\haf\oorc\serializer\ISerializer;
use org\haf\oorc\serializer\NotSerializableException;
use org\haf\oorc\util\ClassStandardization;

class JsonSerializer extends Object implements ISerializer
{

    /**
     * @param ISerializable $object
     * @throws NotSerializableException
     * @return string
     */
    public function serialize($object)
    {
        return json_encode($this->toArrayRecursive($object));
    }

    /**
     * @param IArraiable|array|string $object
     * @return array
     * @throws NotArraiableObjectException
     */
    private function toArrayRecursive($object)
    {
        if (is_array($object)) {
            $newArray = array();
            foreach ($object as $k => $v) {
                $newArray[$k] = $this->toArrayRecursive($v);
            }
            return $newArray;
        } elseif ($object instanceof IArraiable) {
            $objectArray               = $this->toArrayRecursive($object->toArray());
            $objectArray['__object__'] = $object->className(true);
            return $objectArray;
        } elseif (is_object($object)) {
            throw new NotArraiableObjectException('Class %s is not implemented IArraiable interface', get_class(
                $object
            ));
        } else { // null, string, boolean, or number
            return $object;
        }
    }

    /**
     * @param string $string
     * @return ISerializable
     */
    public function unserialize($string)
    {
        $array = json_decode($string, true);
        return $this->fromArrayRecursive($array);
    }

    private function fromArrayRecursive($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $array[$k] = $this->fromArrayRecursive($array[$k]);
            }

            if (isset($array['__object__'])) {
                $className = ClassStandardization::phpizeClassName($array['__object__']);
                if (class_exists($className)) {
                    if (is_subclass_of($className, 'org\haf\oorc\serializer\IArraiable')) {
                        return call_user_func(array($className, 'fromArray'), $array);
                    } else {
                        throw new NotArraiableObjectException('Class %s is not implemented IArraiable interface', $className);
                    }
                }

            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return 'text/json';
    }
}