<?php

namespace LumenPress\Routing\Laravel;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Router extends \Illuminate\Routing\Router
{
    /**
     * Create a new Router instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(Dispatcher $events, Container $container = null)
    {
        parent::__construct($events, $container);
    }

    /**
     * Find the route matching a given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Route
     */
    protected function findRoute($request)
    {
        try {
            $this->current = $route = $this->routes->match($request);
        } catch (\Exception $e) {
            if ($e instanceof NotFoundHttpException) {
                $routeInfo = $this->container['wp.router']->dispatch($request->getMethod(), $request->path());
                if ($routeInfo[0] === 0) {
                    throw new NotFoundHttpException;
                }
                $route = $this->newRoute(
                        $request->getMethod(), $request->path(), $routeInfo[1]
                    )->bind($request);
                // $route->parameterNames = ['post'];
                $route->setParameter('post', $routeInfo[2]);
                $this->current = $route;
            }
        }

        $this->container->instance(Route::class, $route);

        return $route;
    }
}
