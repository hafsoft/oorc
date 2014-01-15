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

use org\haf\shared\php\tool\ObjectFactory;
use org\haf\oorc\object\TObject;
use org\haf\oorc\serializer\IArraiable;
use org\haf\oorc\serializer\ISerializable;

class Exception extends \Exception implements IArraiable
{
    use TObject;

    public function __construct($message = '', $args = null)
    {
        $arr = func_get_args();
        $n   = func_num_args();

        if ($n > 0 && $arr[$n - 1] instanceof \Exception) {
            $previous = $arr[$n - 1];
            array_pop($arr);
            $n--;
        } else {
            $previous = null;
        }


        if ($n > 1) {
            $format  = array_shift($arr);
            $message = vsprintf($format, $arr);
        } elseif ($n == 1) {
            $message = $arr[0];
        } else {
            $message = '';
        }

        parent::__construct($message, 0, $previous);

    }


    /**
     * @return array
     */
    public function toArray()
    {
        if (defined('_RCP_DEBUG') && _RCP_DEBUG) {
            return array(
                '__message' => $this->message,
                '__previous' => $this->getPrevious(),
                '__trace' => $this->getTraceAsString(),
                '__file' => $this->file,
                '__line' => $this->line,
            );
        }
        else {
            return array(
                '__message' => $this->message,
                '__previous' => $this->getPrevious(),
            );
        }
    }

    /**
     * @param array $array
     * @return ISerializable
     */
    public static function fromArray($array)
    {
        /** @var Exception $e */
        // $e = ObjectFactory::constructObject(static::_name());
        $e = new static();
        isset($array['__message']) && $e->message = $array['__message'];
        isset($array['__file']) && $e->file = $array['__file'];
        isset($array['__line']) && $e->line = $array['__line'];
        isset($array['__trace']) && $e->message .= "\n\nTrace:\n" . $array['__trace'];
        return $e;
    }

}