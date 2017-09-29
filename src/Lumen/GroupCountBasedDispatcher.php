<?php

namespace LumenPress\Routing\Lumen;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;

class GroupCountBasedDispatcher extends GroupCountBased
{
    public function dispatch($httpMethod, $uri)
    {
        $routeInfo = parent::dispatch($httpMethod, $uri);

        if (! app()->bound('wp.router')) {
            return $routeInfo;
        }

        if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
            $routeInfo = app('wp.router')->dispatch($httpMethod, $uri);
        }

        if (isset($GLOBALS['wp']) && $routeInfo[0] === Dispatcher::NOT_FOUND) {
            $GLOBALS['wp']->handle_404();
        }

        return $routeInfo;
    }
}
