<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendReminders extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:sendreminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders about any tasks that are due soon or overdue.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Load incomplete tasks due in the next 21 days that aren't recurring.
        $due_soon = \Models\Task::viable()
            ->with('blocker')
            ->where('complete', '<', 1)
            ->where('due', '<', time() + 1814400)
            ->where('recurring', false)
            ->where('due', '>', time())
            ->get();

        // Load incomplete tasks due in the next 7 days that recur.
        $due_soon = $due_soon->merge(
            \Models\Task::viable()
                ->with('blocker')
                ->where('complete', '<', 1)
                ->where('due', '<', time() + 604800)
                ->where('recurring', true)
                ->where('due', '>', time())
                ->get()
        );

        // Load incomplete tasks that are overdue.
        $overdue = \Models\Task::viable()
            ->with('blocker')
            ->where('complete', '<', 1)
            ->where('due', '<', time())
            ->get();

        // Make sure that all the due soon tasks are in season.
        $due_soon = $due_soon->filter(function($task)
        {
            return $task->inSeason();
        });

        // Make sure that all the overdue tasks are in season.
        $overdue = $overdue->filter(function($task)
        {
            return $task->inSeason();
        });

        // If we still have any tasks that are due soon or overdue, send an
        // email reminder.
        if (!$due_soon->isEmpty() || !$overdue->isEmpty()) {
            \Mail::send(
                ['text' => 'emails.reminder'],
                ['due_soon' => $due_soon, 'overdue' => $overdue],
                function($message)
                {
                    foreach (\Models\User::all() as $user) {
                        $message->to($user->email, $user->name);
                    }

                    if (date('N') > 4) {
                        $message->subject('Tasks for the weekend');
                    } else {
                        $message->subject('Tasks for the week');
                    }
                }
            );
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
