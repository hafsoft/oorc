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

namespace org\haf\shared\php;

class ClassLoader
{
    private static $instance = null;
    private $classRoots = array();
    private $registered = false;

    public static function installClassRoot($directory, $prefix = '')
    {
        if (is_dir($directory)) {
            if (self::$instance == null) {
                self::$instance = new ClassLoader();
            }
            return self::$instance->addClassRoot(realpath($directory), $prefix);
        }
        return false;
    }

    private function addClassRoot($directory, $prefix)
    {
        if (!$this->registered) {
            $this->registerLoader();
        }

        $this->classRoots[] = array($directory, $prefix);
        return true;
    }

    private function registerLoader()
    {
        spl_autoload_register(array(&$this, 'autoLoad'));
    }

    public function autoLoad($className)
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        } // for unix
        else {
            $classFile = $className;
        }


        foreach ($this->classRoots as $arr) {
            list ($root, $prefix) = $arr;
            if ($prefix == '' || substr_compare($className, $prefix, 0, strlen($prefix)) == 0) {
                $fileName = $root . DIRECTORY_SEPARATOR . $classFile . '.php';

                if (is_file($fileName)) {
                    /** @noinspection PhpIncludeInspection */
                    include_once $fileName;
                    return true;
                }
            }
        }
        return false;
    }
}