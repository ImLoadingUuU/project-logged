<?php

# Prevent direct file access
if (!defined('APP')) {
    die(header("HTTP/1.1 403 Forbidden"));
}

class Page
{
    /**
     * @var array
     */
    protected static $parameters = [];

    /**
     * @var array
     */
    protected static $messages = [];

    /**
     * @var array
     */
    protected static $scripts = [];

    /**
     * @var array
     */
    protected static $translations = [];

    /**
     * Set page parameters
     * 
     * @param array $parameters
     * @return void
     */
    public static function setParameters(array $parameters)
    {
        self::$parameters = $parameters;
    }

    /**
     * Add message to page
     * 
     * @param array $message
     * @return void
     */
    public static function addMessage(array $message)
    {
        self::$messages[] = $message;
    }

    /**
     * Add scripts to page
     * 
     * @param string $script
     * @return void
     */
    public static function addScript(string $script)
    {
        self::$scripts[] = $script;
    }

    /**
     * Deliver translations
     *  
     * @param array $keys
     * @return void
     */
    public static function deliverTranslations(array $keys)
    {
        self::$translations = $keys;
    }

    /**
     * Get all parameters
     * 
     * @return array
     */
    public static function getParameters()
    {
        return array_merge([
            'title' => 'Something went wrong',
            'page' => 'error.php',
            'layout' => 'main.php'
        ], self::$parameters);
    }

    /**
     * Get single parameter
     * 
     * @param string $key
     * @param mixed $default
     * @param bool $raw
     * 
     * @return mixed
     */
    public static function param(string $key, $default = null, $raw = false)
    {
        if ($key === 'file' && !$raw) {
            return PAGES . '/' . self::$parameters['file'];
        }
        return self::$parameters[$key] ?? $default;
    }

    /**
     * Get all messages
     * 
     * @return array
     */
    public static function getMessage()
    {
        return self::$messages;
    }

    /**
     * Get all scripts
     * 
     * @return array
     */
    public static function getScripts()
    {
        return self::$scripts;
    }

    /**
     * Get translations
     * 
     * @return array
     */
    public static function getTranslations()
    {
        return Arr::only(DICTIONARY, self::$translations);
    }

    /**
     * Render the page
     * 
     * @return void
     */
    public static function render()
    {
        $parameters = self::getParameters();
        self::$parameters = $parameters;
        $file = PAGES . '/layout/' . $parameters['layout'];
        $content = self::param('file');
        if (!file_exists($file) || !file_exists($content)) {
            throw new PageRenderException('Cannot find specified file to render! ' . $file . ' OR ' . $content);
        }
        require $file;
    }
}