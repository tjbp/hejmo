<?php

namespace Controllers;

class Backup extends \Controller
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
     * Output a JSON-formatted backup of the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExport()
    {
        // Cast to object so we don't end up with an array when parsed to JSON.
        $export = (object) [
            'tasks' => \Models\Task::all(),
            'users' => \Models\User::all(),
        ];

        \Log::info("Backup was exported by " . \Request::server('REMOTE_ADDR'));

        $headers = [
            'Content-Disposition' => 'attachment; filename="hejmo-backup-' . time() . '.json"',
        ];

        return \Response::json($export, 200, $headers);
    }

    /**
     * Receive and import a JSON-formatted backup of the database.
     *
     * @return mixed
     */
    public function postImport()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'backup' => 'required|jsonFile',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        $backup = json_decode(file_get_contents(\Input::file('backup')->getRealPath()));

        // Only truncate/import tasks if we have a tasks property.
        if (!empty($backup->tasks)) {
            \Models\Task::truncate();

            foreach ($backup->tasks as $task) {
                \Models\Task::create((array) $task);
            }
        }

        // Only truncate/import users if we have a tasks property.
        if (!empty($backup->users)) {
            \Models\User::truncate();

            foreach ($backup->users as $user) {
                \Models\User::create((array) $user);
            }
        }

        \Log::info("Backup was imported by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }
}
