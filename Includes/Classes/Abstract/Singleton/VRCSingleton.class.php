<?php
class VRCSingleton {
    /**
     * Instance of all class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instances;

    protected function __construct()
    {

    }

    /**
     * Return an instance of 'called' class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of 'called' class.
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class]))
        {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }
}