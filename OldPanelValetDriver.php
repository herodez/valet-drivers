<?php


class OldPanelValetDriver extends ValetDriver
{
    private $pathInfo;

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
         if (file_exists($sitePath.'/protected/config/ext.template.php')) {
             $this->setEnvironmentSettings($uri);
             return true;
         }

        return true;
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
        if (file_exists($staticFilePath = $sitePath.'/public/'.$uri)) {
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
        return $sitePath.'/public/index.php';
    }

    /**
     * @param $uri
     * @return string
     */
    private function getPathInfo($uri)
    {
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->preparePathInfo($uri);
        }
        return $this->pathInfo;
    }

    /**
     * Prepares the path info.
     *
     * @param $uri
     * @return string path info
     */
    private function preparePathInfo($uri)
    {
        return preg_replace('/^(.+?\.php)/', '', $uri);
    }

    /**
     * @param $uri
     */
    private function setEnvironmentSettings($uri)
    {
        $_SERVER['PATH_INFO'] = $this->getPathInfo($uri);
        \putenv("APPLICATION_ENV=dev");
    }
}