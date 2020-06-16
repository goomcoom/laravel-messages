<?php


namespace GoomCoom\Messages\Test;


use GoomCoom\Messages\Facades\Messages;
use GoomCoom\Messages\MessagesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return MessagesServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [MessagesServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Messages' => Messages::class,
        ];
    }
}