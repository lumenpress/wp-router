<?php

namespace LumenPress\Routing\Tests;

use LumenPress\Nimble\Models\Post;

class SingularTest extends TestCase
{
    public function testRoute()
    {
        $this->setPermalinkStructure('/%year%/%monthnum%/%day%/%postname%/');

        $app = $this->createApplication();

        $app['wp.router']->is('singular', function () {
            return response('test singular route');
        });

        $post = wp_insert_post([
            'post_title'    => 'test singular route',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $response = $this->callPostUrl($app, $post);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test singular route', $response->getContent());
    }

    public function testRoute2()
    {
        $app = $this->createApplication();

        $app['wp.router']->is('singular', function ($post) {
            return get_class($post);
        });

        $id = wp_insert_post([
            'post_title'    => 'test singular route 2',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $post = get_post($id);

        $response = $this->callPostUrl($app, $post);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(Post::getClassNameByType($post->post_type, Post::class), $response->getContent());
    }

    public function testRoute3()
    {
        $app = $this->createApplication();

        $app['wp.router']->is(['singular' => 'page'], function ($post) {
            return $post->title;
        });

        $id = wp_insert_post([
            'post_title'    => 'test singular route 3',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $response = $this->callPostUrl($app, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test singular route 3', $response->getContent());
    }

    public function testRoute4()
    {
        $app = $this->createApplication();

        $app['wp.router']->is(['singular' => 'post'], function ($post) {
            return $post->title;
        });

        $id = wp_insert_post([
            'post_title'    => 'test singular route 4',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $response = $this->callPostUrl($app, $id);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testRoute5()
    {
        $app = $this->createApplication();

        $app['wp.router']->is(['singular' => 'page'], function ($post) {
            return 'test singular route 5.1';
        });

        $app['wp.router']->is('singular', function ($post) {
            return 'test singular route 5.2';
        });

        $id = wp_insert_post([
            'post_title'    => 'test singular route 5.1',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $response = $this->callPostUrl($app, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test singular route 5.1', $response->getContent());

        $id = wp_insert_post([
            'post_title'    => 'test singular route 5.2',
            'post_status'   => 'publish',
        ]);

        $response = $this->callPostUrl($app, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test singular route 5.2', $response->getContent());
    }
}
