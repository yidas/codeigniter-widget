<?php

namespace yidas;

use ReflectionClass;

/**
 * Widget
 * 
 * @author  Nick Tsai
 * @version 1.0.0
 */
class Widget
{
    /**
     * CodeIgniter object
     *
     * @var object
     */
    protected $CI;
    
    /**
     * @var object Widget object instance
     */
    private static $_instance;

    /**
     * @var string Widget view path cache
     */
    private static $_viewPath;

    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->init();
    }
    
    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * 
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     * @throws \Exception
     */
    public static function widget($config=[])
    {
        // Cache considering (Lost resetting pattern)
        // if (!self::$_instance) {
        //     $widget = get_called_class();
        //     self::$_instance = new $widget;
        // }
        
        // Create an instance for each call
        $widgetClass = get_called_class();
        self::$_instance = new $widgetClass;

        // Configuration for each call
        foreach ($config as $property => $value) {
            
            self::$_instance->$property = $value;
        }
        
        return self::$_instance->run();
    }

    /**
     * Returns the directory containing the view files for this widget.
     * The default implementation returns the 'views' subdirectory under the directory containing the widget class file.
     * 
     * @return string the directory containing the view files for this widget.
     */
    public function getViewPath()
    {
        // Cache mechanism
        if (self::$_viewPath) {
            return self::$_viewPath;
        }
        
        // Called widget filepath
        $reflectionClass = new ReflectionClass($this);
        $widgetPath = dirname($reflectionClass->getFileName());
        // For Codeigniter view which doesn't support absolute path
        $relativePath = str_replace(APPPATH, '', $widgetPath);

        // Real relative view path for Codeigniter
        return self::$_viewPath = "../{$relativePath}/views/";
    }

    /**
     * Render a view of Widget
     * 
     * @param string View file path, relative for widget's view, absolute for CI view
     * @param array View variables
     */
    protected function render($viewFile, $variables=[])
    {
        $viewFile = (strpos($viewFile, '/', 0)===false) ? $this->getViewPath() . $viewFile : $viewFile;
        
        $this->CI->load->view($viewFile, $variables);
    }

    /**
     * Initialization
     *
     * @return void
     */
    public function init()
    {
        // For overriding
    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        // For overriding
    }
}
