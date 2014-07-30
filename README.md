hejmo
=====

Lightweight task management written in PHP with Laravel.

## Introduction
Hejmo is a simple web application for managing tasks, primarily geared towards time-critical household chores and land management. Tasks are comprised of a text description and a due date, with an option to have it recur over a certain time period. Due 

As a usage example, I use Hejmo to keep track of mowing the lawn and maintaining my classic cars.

## Features
- Task dependencies: drag and drop tasks to the side of other tasks to structure a dependency tree.
- Percentage completion: mark your tasks as partially complete.
- Recurring tasks: configure a task to repeat over a specific time period, with an optional seasonal limit.
- User list: email reminders to multiple users.
- Backup: Import/export all your tasks and users to a single file.
- Database flexibility: support for MySQL, PostgreSQL, SQLite and Microsoft SQL Server.
- Mailer flexibility: support for a remote SMTP server, local Sendmail, [Mailgun](http://www.mailgun.com/) and [Mandrill](http://www.mandrill.com/).

## Installation
### Download Hejmo
Clone the repository or download/extract the archive to your webserver.
### Download dependencies
Run install.php in the root directory (`php path/to/hejmo/install.php`), which will install dependencies into vendor/ and check permissions on directories.
### Configure your web server
Configure your web server to point to the public/ directory.
#### Apache
An .htaccess file is present to handle URL rewriting to public/index.php. Make sure you have [`AllOverride All`](https://httpd.apache.org/docs/2.4/mod/core.html#allowoverride) configured.
#### Others (eg. NGINX)
You must configure all URLs to be rewritten to index.php unless they match a valid path to a file in public/.
### Add the cron jobs
There are two scripts that can be run incrementally. The first will send an email reminder about the tasks to all configured users. This example will send emails out at 0800 on Mondays and Fridays:

    0 8 * * 1,5 user /srv/hejmo/artisan command:sendreminders

The second script handles recurring tasks that have since passed their due date. It bumps the due date to the next due date, clones the task so that the fact it isn't complete is not forgotten, and emails the users. This example will handle this every day at 0758 (just before 0800 so it runs before the first cron job):

    58 7 * * * user /srv/hejmo/artisan command:bumprecurring
    
### Load up
Point your browser at your web server - Hejmo will catch any request path and redirect you to its installation page. From there just follow the steps to configure your database and other settings!

## Known Issues
- No permissions system or user attribution for tasks. Access is entirely governed by your web server.