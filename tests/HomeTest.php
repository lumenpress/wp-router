<?php

namespace LumenPress\Routing\Tests;

class HomeTest extends TestCase
{
    public function testRoute()
    {
        $this->setPermalinkStructure('/%year%/%monthnum%/%day%/%postname%/');

        $app = $this->createApplication();

        $app['wp.router']->is('home', function () {
            return response('Hello World');
        });

        $response = $this->call($app, '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testRoute2()
    {
        $app = $this->createApplication();

        $app->router->get('/', function () {
            return response('high priority');
        });

        $app['wp.router']->is('home', function () {
            return response('low priority');
        });

        $response = $this->call($app, '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('high priority', $response->getContent());
    }

    public function testRoute3()
    {
        $app = $this->createApplication();

        $app['wp.router']->is('home', function () {
            return response('low priority');
        });

        $app->router->get('/', function () {
            return response('high priority');
        });

        $response = $this->call($app, '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('high priority', $response->getContent());
    }
}
