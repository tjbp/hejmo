<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BumpRecurring extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:bumprecurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bump the due date for overdue recurring tasks and send an email.';

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
        // Load recurring tasks that are overdue, regardless of completion.
        $overdue = \Models\Task::with('blocker')
            ->where('due', '<', time())
            ->where('recurring', true)
            ->get();

        // Filter out tasks that are not in season.
        $overdue = $overdue->filter(function($task)
        {
            return $task->inSeason();
        });

        // Filter out tasks that are incomplete and require an email reminder.
        $overdue_email = $overdue->filter(function($task)
        {
            return $task->complete < 1;
        });

        // Send an email notification for any tasks being bumped that aren't
        // completed.
        if (!$overdue_email->isEmpty()) {
            \Mail::send(
                ['text' => 'emails.recurring'],
                ['tasks' => $overdue_email],
                function($message) use ($overdue_email)
                {
                    foreach (\Models\User::all() as $user) {
                        $message->to($user->email, $user->name);
                    }

                    if (count($overdue_email) > 1) {
                        $message->subject('Tasks due');
                    } else {
                        $message->subject('Task due');
                    }
                }
            );
        }

        // Bump every recurring task that's overdue.
        foreach ($overdue as $task) {
            $task->bumpRecurring();

            $task->save();
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
