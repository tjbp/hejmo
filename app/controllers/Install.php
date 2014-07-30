<?php

namespace Controllers;

class Install extends \Controller
{
    /**
     * Return the installation page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return \View::make('install');
    }

    /**
     * Run an installation test and redirect to the installation page on failure.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTest()
    {
        if ($errors = self::test()) {
            return \Redirect::to('install')->withErrors($errors);
        }

        return \Redirect::to('');
    }

    /**
     * Tests configuration values and critical services to ensure stable
     * operation.
     *
     * @return array
     */
    public static function test()
    {
        $errors = [];

        // Ensure we aren't missing values necessary for our database driver.
        switch (\Config::get('database.connections.hejmo.driver')) {
            case 'mysql':
            case 'pgsql':
            case 'sqlsrv':
                if (!\Config::get('database.connections.hejmo.host')) {
                    $errors[] = 'Please configure a database host';
                }

                if (!\Config::get('database.connections.hejmo.database')) {
                    $errors[] = 'Please configure a database name';
                }

                if (!\Config::get('database.connections.hejmo.username')) {
                    $errors[] = 'Please configure a database user';
                }

                break;
            case 'sqlite':
                if (!\Config::get('database.connections.hejmo.database')) {
                    $errors[] = 'Please configure a database file';
                } elseif (!is_writable(\Config::get('database.connections.hejmo.database'))) {
                    $errors[] = 'The database file cannot be written to';
                }

                break;
            default:
                $errors[] = 'Please configure a database';
        }

        // Test database connection if we have no config problems.
        if (empty($errors)) {
            // Attempt to load the migrations table as a test.
            try {
                \DB::table('migrations')->get();
            // Catch a QueryException, which will be thrown if the migrations
            // table is missing.
            } catch (\Illuminate\Database\QueryException $e) {
                // If the migrations table is missing, we automatically run
                // a full migration, though only if we're not in a dev
                // environment (to prevent headaches).
                if (\App::environment('hejmo')) {
                    \Artisan::call('migrate:install');

                    // Using the force option will bypass stdin confirmation.
                    \Artisan::call('migrate', ['--force' => true]);
                }
            // Catch a PDOException, which will be thrown if there's a
            // connection problem.
            } catch (\PDOException $e) {
                // A reportedly missing PDO driver is very likely to be an
                // unloaded extension.
                if (preg_match('#could not find driver#', $e->getMessage())) {
                    $errors[] = 'PDO ' . \Config::get('database.connections.hejmo.driver') . ' extension not loaded';
                } else {
                    $errors[] = $e->getMessage();
                }
            }
        }

        if (!\Config::get('app.url')) {
            $errors[] = 'Please configure a site URL';
        }

        if (!\Config::get('mail.from.address') || !\Config::get('mail.from.name')) {
            $errors[] = 'Please configure a name and address to send emails from';
        }

        // Ensure we aren't missing values necessary for our mail driver.
        switch (\Config::get('mail.driver')) {
            case 'smtp':
                if (!\Config::get('mail.host')) {
                    $errors[] = 'Please configure an SMTP host';
                }

                if (!\Config::get('mail.port')) {
                    $errors[] = 'Please configure an SMTP port';
                }

                break;
            case 'mailgun':
                if (!\Config::get('services.mailgun.domain')) {
                    $errors[] = 'Please configure your Mailgun domain';
                }

                if (!\Config::get('services.mailgun.secret')) {
                    $errors[] = 'Please configure your Mailgun API key';
                }

                break;
            case 'mandrill':
                if (!\Config::get('services.mandrill.secret')) {
                    $errors[] = 'Please configure your Mandrill API key';
                }

                break;
            case 'mail':
            case 'sendmail':
            case 'log':
                break;
            default:
                $errors[] = 'Please configure an email agent';
        }

        return $errors;
    }
}
