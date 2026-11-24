<?php

namespace LaveroAdmin\Admin\Scheduling;

use Illuminate\Support\ServiceProvider;

class SchedulingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lavero-admin-scheduling');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lavero-admin-scheduling');

        Scheduling::boot();
    }
}
