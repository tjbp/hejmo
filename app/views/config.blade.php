{{ Form::open(['url' => "/config/update", 'class' => "pure-form pure-form-aligned config"]) }}
    <h1>Configuration</h1>
    <fieldset>
        <legend><i class="fa fa-hand-o-up"></i> Links</legend>
        <div class="pure-control-group">
            <label>Site Url</label>
            {{ Form::text('app_url', \Config::get('app.url'), ['placeholder' => "eg. http://www.example.com"]) }}
        </div>
    </fieldset>
    <fieldset>
        <legend><i class="fa fa-database"></i> Database</legend>
        <div class="pure-control-group">
            <label>Backend</label>
            <div>
                <label>
                    {{ Form::radio('database_connections_hejmo_driver', 'mysql', \Config::get('database.connections.hejmo.driver') == 'mysql') }} MySQL
                </label>
                <label>
                    {{ Form::radio('database_connections_hejmo_driver', 'pgsql', \Config::get('database.connections.hejmo.driver') == 'pgsql') }} PostgreSQL
                </label>
                <label>
                    {{ Form::radio('database_connections_hejmo_driver', 'sqlite', \Config::get('database.connections.hejmo.driver') == 'sqlite') }} SQLite
                </label>
                <label>
                    {{ Form::radio('database_connections_hejmo_driver', 'sqlsrv', \Config::get('database.connections.hejmo.driver') == 'sqlsrv') }} Microsoft SQL Server
                </label>
            </div>
        </div>
        <div class="database mysql pgsql sqlsrv">
            <div class="pure-control-group">
                <label>Host</label>
                {{ Form::text('database_connections_hejmo_host', \Config::get('database.connections.hejmo.host'), ['placeholder' => "eg. localhost"]) }}
            </div>
            <div class="pure-control-group">
                <label>Port</label>
                {{ Form::text('database_connections_hejmo_port', \Config::get('database.connections.hejmo.port'), ['placeholder' => "eg. 3306"]) }}
            </div>
        </div>
        <div class="database mysql pgsql sqlsrv sqlite">
            <div class="pure-control-group">
                <label>Database</label>
                {{ Form::text('database_connections_hejmo_database', \Config::get('database.connections.hejmo.database'), ['placeholder' => "eg. hejmo"]) }}
            </div>
        </div>
        <div class="database mysql pgsql sqlsrv">
            <div class="pure-control-group">
                <label>User</label>
                {{ Form::text('database_connections_hejmo_username', \Config::get('database.connections.hejmo.username')) }}
            </div>
            <div class="pure-control-group">
                <label>Password</label>
                {{ Form::text('database_connections_hejmo_password', \Config::get('database.connections.hejmo.password')) }}
            </div>
        </div>
        <div class="database mysql pgsql sqlsrv sqlite">
            <div class="pure-control-group">
                <label>Table prefix</label>
                {{ Form::text('database_connections_hejmo_prefix', \Config::get('database.connections.hejmo.prefix'), ['placeholder' => "eg. hejmo_"]) }}
            </div>
        </div>
        <div class="database mysql pgsql">
            <div class="pure-control-group">
                <label>Charset</label>
                {{ Form::text('database_connections_hejmo_charset', \Config::get('database.connections.hejmo.charset'), ['placeholder' => "eg. utf8"]) }}
            </div>
        </div>
        <div class="database mysql">
            <div class="pure-control-group">
                <label>Collation</label>
                {{ Form::text('database_connections_hejmo_collation', \Config::get('database.connections.hejmo.collation'), ['placeholder' => "eg. utf8_unicode_ci"]) }}
            </div>
        </div>
        <div class="database pgsql">
            <div class="pure-control-group">
                <label>Schema</label>
                {{ Form::text('database_connections_hejmo_schema', \Config::get('database.connections.hejmo.schema'), ['placeholder' => "eg. public"]) }}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><i class="fa fa-envelope"></i> Email</legend>
        <div class="pure-control-group">
            <label>From address</label>
            {{ Form::email('mail_from_address', \Config::get('mail.from.address'), ['placeholder' => "eg. hejmo@example.com"]) }}
        </div>
        <div class="pure-control-group">
            <label>From name</label>
            {{ Form::text('mail_from_name', \Config::get('mail.from.name'), ['placeholder' => "eg. Hejmo"]) }}
        </div>
        <div class="pure-control-group">
            <label>Driver</label>
            <div>
                <label>
                    {{ Form::radio('mail_driver', 'smtp', \Config::get('mail.driver') == 'smtp') }} SMTP
                </label>
                <label>
                    {{ Form::radio('mail_driver', 'mail', \Config::get('mail.driver') == 'mail') }} PHP mail()
                </label>
                <label>
                    {{ Form::radio('mail_driver', 'sendmail', \Config::get('mail.driver') == 'sendmail') }} Sendmail
                </label>
                <label>
                    {{ Form::radio('mail_driver', 'mailgun', \Config::get('mail.driver') == 'mailgun') }} Mailgun ({{ link_to('http://www.mailgun.com/') }})
                </label>
                <label>
                    {{ Form::radio('mail_driver', 'mandrill', \Config::get('mail.driver') == 'mandrill') }} Mandrill ({{ link_to('http://www.mandrill.com/') }})
                </label>
                <label>
                    {{ Form::radio('mail_driver', 'log', \Config::get('mail.driver') == 'log') }} Log (debugging)
                </label>
            </div>
        </div>
        <div class="email smtp">
            <div class="pure-control-group">
                <label>Host</label>
                {{ Form::text('mail_host', \Config::get('mail.host'), ['placeholder' => "eg. smtp.example.com"]) }}
            </div>
            <div class="pure-control-group">
                <label>Port</label>
                {{ Form::text('mail_port', \Config::get('mail.port'), ['placeholder' => "eg. 25"]) }}
            </div>
            <div class="pure-control-group">
                <label>Encryption</label>
                {{ Form::radio('mail_encryption', 'tls', \Config::get('mail.encryption') == 'tls') }} TLS
            </div>
            <div class="pure-control-group">
                <label></label>
                {{ Form::radio('mail_encryption', 'ssl', \Config::get('mail.encryption') == 'ssl') }} SSL
            </div>
            <div class="pure-control-group">
                <label></label>
                {{ Form::radio('mail_encryption', '', \Config::get('mail.encryption') == '') }} None
            </div>
            <div class="pure-control-group">
                <label>Username</label>
                {{ Form::text('mail_username', \Config::get('mail.username')) }}
            </div>
            <div class="pure-control-group">
                <label>Password</label>
                {{ Form::text('mail_password', \Config::get('mail.password')) }}
            </div>
        </div>
        <div class="email mailgun">
            <div class="pure-control-group">
                <label>Domain</label>
                {{ Form::text('services_mailgun_domain', \Config::get('services.mailgun.domain'), ['placeholder' => "eg. example.com"]) }}
            </div>
            <div class="pure-control-group">
                <label>API key</label>
                {{ Form::text('services_mailgun_secret', \Config::get('services.mailgun.secret')) }}
            </div>
        </div>
        <div class="email mandrill">
            <div class="pure-control-group">
                <label>API key</label>
                {{ Form::text('services_mandrill_secret', \Config::get('services.mandrill.secret')) }}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><i class="fa fa-code"></i> Developer</legend>
        <div class="pure-control-group">
            <label>Debug mode</label>
            {{ Form::checkbox('app_debug', 1, \Config::get('app.debug') == true) }}
        </div>
    </fieldset>
    <button type="submit" class="pure-button pure-button-primary"><i class="fa fa-check"></i> Save</button>
{{ Form::close() }}
