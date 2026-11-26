<?php

namespace LaveroAdmin\Admin\Scheduling;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
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

        $this->app->booted(function () {
            if (!$this->app->runningInConsole()) {
                return;
            }

            try {
                $disabled = Cache::get('lavero_scheduling_disabled', []);
                if (empty($disabled) || !is_array($disabled)) {
                    return;
                }

                $schedule = $this->app->make(Schedule::class);
                foreach ($schedule->events() as $event) {
                    $key = $event->command ?? $event->description ?? '';
                    $event->when(static fn () => !in_array($key, $disabled, true));
                }
            } catch (\Throwable) {
            }
        });
    }
}
