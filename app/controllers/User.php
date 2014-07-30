<?php

namespace Controllers;

class User extends \Controller
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
     * Receive, validate and add a new user to the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNew()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'email' => 'required|email',
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        $user = new \Models\User;

        $user->email = \Input::get('email');

        $user->name = \Input::get('name');

        $user->save();

        \Log::info("User {$user->userId} was added by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }

    /**
     * Edit a user's name and email address.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'user_id' => 'required|integer',
                'email' => 'required|email',
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        if (!$user = \Models\User::find(\Input::get('user_id'))) \App::abort(404, 'User not found');

        $user->email = \Input::get('email');

        $user->name = \Input::get('name');

        \Log::info("User {$user->userId} was edited by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::back();
    }

    /**
     * Delete a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete()
    {
        $validator = \Validator::make(
            \Input::all(),
            [
                'user_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        if (!$user = \Models\User::find(\Input::get('user_id'))) \App::abort(404, 'User not found');

        \Log::info("User {$user->userId} was deleted by " . \Request::server('REMOTE_ADDR'));

        $user->delete();

        return \Redirect::back();
    }
}
