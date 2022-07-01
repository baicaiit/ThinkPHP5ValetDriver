<?php

class ThinkPHP5ValetDriver extends ValetDriver
{

    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return file_exists($sitePath . '/public/index.php') &&
            file_exists($sitePath . '/think');
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        if (
            file_exists($staticFilePath = $sitePath . '/public' . $uri)
            && is_file($staticFilePath)
        ) {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'], $_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }

        if (empty($_SERVER['PATH_INFO']) && !is_file($sitePath . '/public' . $uri)) {
            $_SERVER['PATH_INFO'] = $uri;
            $_SERVER['SCRIPT_NAME'] = '/index.php';
        }
        $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/public' . $_SERVER['SCRIPT_NAME'];

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
