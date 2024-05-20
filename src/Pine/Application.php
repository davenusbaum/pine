<?php

namespace Pine;

use Pine\Router;

/**
 * Application represents the web application
 */
class Application
{
    /** @var Router */
    public $router;

    /** @var ArrayMap */
    protected $settings;

    /**
     * Construct an Application object
     * @param array $tree An optional precompiled router tree
     */
    public function __construct($tree = []) {
        $this->router = new Router($tree);
        $this->settings = new ArrayMap([
            'trust proxy' => false,
            'views' => dirname(getcwd()).'/views'
        ]);
    }

    /**
     * Get an application setting
     * @param string $name The setting name
     * @return mixed
     */
    public function get($name) {
        return $this->settings->get($name);
    }

    /**
     * Handle a request
     * @param array $server the $_SERVER array
     * @param array $get the $_GET array
     * @param array $post the $_POST array
     * @param array $cookie The $_COOKIE array
     */
    public function handle($server, $get, $post, $cookie)
    {
        $req = new Request($this, $server, $get, $post, $cookie);
        if ($req->route) {
            $req->route->run($req, $req->res);
        }
    }

    /**
     * Set an application setting
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set($name, $value) {
        $this->settings->set($name, $value);
    }
}