<?php

namespace LaveroAdmin\Admin\Scheduling;

use Illuminate\Http\Request;
use LaveroAdmin\Admin\Facades\Admin;
use LaveroAdmin\Admin\Layout\Content;

class SchedulingController
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('lavero-admin-scheduling::scheduling.task_scheduling'));

            $scheduling = new Scheduling();

            $content->body(view('lavero-admin-scheduling::index', [
                'events'   => $scheduling->getTasks(),
                'history'  => \Illuminate\Support\Facades\Cache::get('lavero_scheduling_history', []),
                'disabled' => \Illuminate\Support\Facades\Cache::get('lavero_scheduling_disabled', []),
            ]));
        });
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function runEvent(Request $request)
    {
        $scheduling = new Scheduling();
        $id = $request->get('id');
        $startedAt = microtime(true);

        try {
            $output = $scheduling->runTask($id);
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
            $history = \Illuminate\Support\Facades\Cache::get('lavero_scheduling_history', []);
            $history[$id] = [
                'last_run_at' => now()->toDateTimeString(),
                'duration_ms' => $durationMs,
                'status'      => 'success',
            ];
            \Illuminate\Support\Facades\Cache::forever('lavero_scheduling_history', $history);

            return [
                'status'    => true,
                'message'   => 'success',
                'data'      => $output,
                'duration'  => $durationMs,
            ];
        } catch (\Exception $e) {
            $history = \Illuminate\Support\Facades\Cache::get('lavero_scheduling_history', []);
            $history[$id] = [
                'last_run_at' => now()->toDateTimeString(),
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'status'      => 'failed',
            ];
            \Illuminate\Support\Facades\Cache::forever('lavero_scheduling_history', $history);

            return [
                'status'    => false,
                'message'   => 'failed',
                'data'      => $e->getMessage(),
            ];
        }
    }

    public function toggle(Request $request)
    {
        $task = (string) $request->input('task');
        if ($task === '') {
            return ['status' => false, 'message' => 'Task identifier is required'];
        }

        $disabled = \Illuminate\Support\Facades\Cache::get('lavero_scheduling_disabled', []);
        if (!is_array($disabled)) {
            $disabled = [];
        }

        if (in_array($task, $disabled, true)) {
            $disabled = array_values(array_diff($disabled, [$task]));
            $isDisabled = false;
        } else {
            $disabled[] = $task;
            $isDisabled = true;
        }

        \Illuminate\Support\Facades\Cache::forever('lavero_scheduling_disabled', $disabled);

        return [
            'status'   => true,
            'disabled' => $isDisabled,
        ];
    }
}
