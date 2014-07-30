<?php

namespace Controllers;

class Config extends \Controller
{
    /**
     * Receive, validate and save custom configuration values.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate()
    {
        // Config values that can be updated. This array's keys are also used
        // to cycle through the configuration options, hence why some are
        // there despite not having rules.
        $rules = [
            'app_debug' => 'boolean',
            'app_url' => 'required|url',
            'database_connections_hejmo_driver' => 'required|in:mysql,pgsql,sqlsrv,sqlite',
            'database_connections_hejmo_host' => 'required',
            'database_connections_hejmo_port' => 'integer',
            'database_connections_hejmo_database' => 'required',
            'database_connections_hejmo_username' => 'required',
            'database_connections_hejmo_password' => '',
            'database_connections_hejmo_prefix' => '',
            'database_connections_hejmo_charset' => '',
            'database_connections_hejmo_collation' => '',
            'database_connections_hejmo_schema' => '',
            'mail_from_address' => 'required|regex:#@#',
            'mail_from_name' => 'required',
            'mail_driver' => 'required|in:smtp,mail,sendmail,mailgun,mandrill,log',
            'mail_host' => '',
            'mail_port' => 'integer',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'in:tls,ssl',
            'services_mailgun_domain' => '',
            'services_mailgun_secret' => '',
            'services_mandrill_secret' => '',
        ];

        $validator = \Validator::make(
            \Input::all(),
            $rules
        );

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }

        foreach ($rules as $config_id => $rule) {
            \Config::set(str_replace('_', '.', $config_id), \Input::get($config_id));
        }

        try {
            \Config::save();
        } catch (\ErrorException $e) {
            if (preg_match('#Permission denied#', $e->getMessage())) {
                return \Redirect::back()->withErrors(['app/config/hejmo is not writable']);
            }
        }

        \Log::info("Configuration was updated by " . \Request::server('REMOTE_ADDR'));

        return \Redirect::to('install/test');
    }
}
