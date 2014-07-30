<?php

namespace Controllers;

class Task extends \Controller
{
    /**
     * Schedule any filters.
     *
     * @return void
     */
    public function __construct()
    {
        $this->beforeFilter('install');
    }

    /**
     * Populate and return our tasks list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $log_file = app_path() . '/storage/logs/laravel.log';

        return \View::make('tasks', [
            // Optionally load in the log, since it is included in the admin footer.
            'log' => (!file_exists($log_file)) ? '' : file_get_contents($log_file),
            'tasks' => \Models\Task::with('blockees', 'blocker')
                ->where('complete', '<', 1)
                ->where(function($query)
                {
                    $query->has('blocker', 0)
                        ->orWhereHas('blocker', function($query)
                        {
                            $query->where('complete', 1);
                        });
                })
                // Cast due column as signed to prevent a range error with
                // dates in the past.
                ->orderBy(\DB::raw("CAST(`due` AS signed) - UNIX_TIMESTAMP()"))
                ->get(),
            'complete_tasks' => \Models\Task::where('complete', 1)
                // Cast due column as signed to prevent a range error with
                // dates in the past.
                ->orderBy(\DB::raw("CAST(`due` AS signed) - UNIX_TIMESTAMP()"))
                ->get(),
            'users' => \Models\User::all(),
        ]);
    }

    /**
     * Receive, validate and add a new task to the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNew()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'description' => 'required',
                'due' => 'required|date|future',
                'gap' => 'integer',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        $task = new \Models\Task;

        $task->description = \Input::get('description');

        $task->added = time();

        $task->due = strtotime(\Input::get('due'));

        if ($task->recurring = (bool) \Input::get('recurring')) {
            $task->gap = \Input::get('gap');

            // Multiply our gap by the chosen modifier.
            switch (\Input::get('gap_unit')) {
                case $task::DAY:
                    $task->gap = $task->gap * $task::DAY;

                    break;
                case $task::WEEK:
                    $task->gap = $task->gap * $task::WEEK;

                    break;
                case $task::MONTH:
                    $task->gap = $task->gap * $task::MONTH;

                    break;
                case $task::YEAR:
                    $task->gap = $task->gap * $task::YEAR;

                    break;
                default:
                    $task->recurring = false;
            }
        }

        // Apply any seasonal restriction (bit-wise).
        if (!(bool) \Input::get('limit_season')) {
            $task->season = 0;
        } else {
            if (\Input::get('winter')) $task->season |= $task::WINTER;

            if (\Input::get('spring')) $task->season |= $task::SPRING;

            if (\Input::get('summer')) $task->season |= $task::SUMMER;

            if (\Input::get('autumn')) $task->season |= $task::AUTUMN;
        }

        $task->complete = 0.00;

        $task->save();

        \Log::info("Task {$task->taskId} was added by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }

    /**
     * Receive and validate a new blocking task for a task.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBlocker()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'task_id' => 'required|integer',
                'blocker_id' => 'integer',
            ]
        );

        if ($validator->fails()) {
            \App::abort(400, 'Insufficient parameters');
        }

        if (!$task = \Models\Task::find(\Input::get('task_id'))) \App::abort(404, 'Task not found');

        // Check if we're disassociating from a blocking task.
        if (!\Input::get('blocker_id')) {
            $task->blockerId = 0;

            $task->save();
        } else {
            if (!$blocker = \Models\Task::find(\Input::get('blocker_id'))) \App::abort(404, 'Blocker task not found');

            // A task cannot block itself or a task that's blocking it.
            // @todo Check the tasks entire blocking hierarchy too.
            if (($task->taskId != $blocker->taskId) && ($task->taskId != $blocker->blockerId)) {
                $blocker->blockees()->save($task);
            }
        }
    }

    /**
     * Receive and validate a new description for a task.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'task_id' => 'required|integer',
                'description' => 'required',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        if (!$task = \Models\Task::find(\Input::get('task_id'))) \App::abort(404, 'Task not found');

        $task->description = \Input::get('description');

        $task->save();

        \Log::info("Task {$task->taskId} was edited by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }

    /**
     * Delete a task.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'task_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        if (!$task = \Models\Task::find(\Input::get('task_id'))) \App::abort(404, 'Task not found');

        // Ensure any tasks blocked by this task are set to this task's blocker
        // (which may be zero).
        \Models\Task::where('blocker_id', $task->taskId)->update(['blocker_id' => $task->blockerId]);

        $task->delete();

        \Log::info("Task {$task->taskId} was deleted by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }

    /**
     * Complete a task.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postComplete()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'task_id' => 'required|integer',
                'complete' => 'required',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        if (!$task = \Models\Task::find(\Input::get('task_id'))) \App::abort(404, 'Task not found');

        $task->complete = \Input::get('complete');

        $task->save();

        \Log::info("Task {$task->taskId} was completed " . $task->complete * 100 . "% by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }
}
