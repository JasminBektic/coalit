<?php

namespace Coalition;

class ConfigRepository extends \ArrayObject
{
    /**
     * @var array
     */
    private $configValues;

    /**
     * ConfigRepository Constructor
     */
    public function __construct($configValues = null)
    {
       $this->configValues = $configValues;
    }

    /**
     * Determine whether the config array contains the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $iterator  = new \RecursiveArrayIterator($this->configValues);
        $recursive = new \RecursiveIteratorIterator(
            $iterator,
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($recursive as $k => $value) {
            if (strpos($k, $key) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set a value on the config array
     *
     * @param string $key
     * @param mixed  $value
     * @return \Coalition\ConfigRepository
     */
    public function set($key, $value)
    {
        $this->configValues[$key] = $value;

        return $this;
    }

    /**
     * Get an item from the config array
     *
     * If the key does not exist the default
     * value should be returned
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->configValues[$key]) ? $this->configValues[$key] : $default;
    }

    /**
     * Remove an item from the config array
     *
     * @param string $key
     * @return \Coalition\ConfigRepository
     */
    public function remove($key)
    {
        unset($this->configValues[$key]);

        return $this;
    }

    /**
     * Load config items from a file or an array of files
     *
     * The file name should be the config key and the value
     * should be the return value from the file
     * 
     * @param array|string The full path to the files $files
     * @return void
     */
    public function load($files)
    {
        if (is_string($files)) {
            $key = pathinfo($files, PATHINFO_FILENAME);
            $this->configValues[$key] = include_once $files;
        }

        if (is_array($files)) {
            $this->configValues = [];
            foreach($files as $file) {
                $key = pathinfo($file, PATHINFO_FILENAME);
                $this->configValues[$key] = include_once $file;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key) {
        return $this->configValues[$key];
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function offsetSet($key, $value) {
        $this->configValues[$key] = $value;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function offsetExists($key) {
        return isset($this->configValues[$key]);
    }

    /**
     * @param string $key
     * @return void
     */
    public function offsetUnset($key) {
        unset($this->configValues[$key]);
    }
}