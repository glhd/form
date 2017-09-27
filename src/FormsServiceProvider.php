<?php

namespace Galahad\Forms;

use Galahad\Forms\ErrorStore\IlluminateErrorStore;
use Galahad\Forms\OldInput\IlluminateOldInputProvider;
use Illuminate\Support\ServiceProvider;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Register to container.
     */
    public function register()
    {
        $this->registerErrorStore()->registerOldInput()->registerFormBuilder();
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            'galahad.form',
            'galahad.form.errorstore',
            'galahad.form.oldinput',
        ];
    }

    /**
     * @return $this
     */
    protected function registerErrorStore()
    {
        $this->app->singleton('galahad.form.errorstore', function ($app) {
            return new IlluminateErrorStore($app['session.store']);
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerOldInput()
    {
        $this->app->singleton('galahad.form.oldinput', function ($app) {
            return new IlluminateOldInputProvider($app['session.store']);
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('galahad.form', function ($app) {
            return (new FormBuilder())
                ->setErrorStore($app['galahad.form.errorstore'])
                ->setOldInputProvider($app['galahad.form.oldinput'])
                ->setToken(optional($app['session.store'])->token());
        });

        return $this;
    }
}