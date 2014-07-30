<div class="backup">
    <h1>Backup</h1>
    <a href="/backup/export" title="Export" class="pure-button pure-button-primary"><i class="fa fa-download"></i> Export</a>
    {{ Form::open(['url' => "/backup/import", 'class' => "pure-form", 'files' => true]) }}
        {{ Form::file('backup') }}
        <button type="submit" class="pure-button pure-button-primary"><i class="fa fa-upload"></i> Import</button>
    {{ Form::close() }}
</div>
