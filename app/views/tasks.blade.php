@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
@foreach ($tasks as $task)
    @include('task')
@endforeach
@foreach ($complete_tasks as $task)
    @include('task', ['no_blockees' => true])
@endforeach
<div class="pure-u-1-1">
    <div class="pure-g">
        <div class="pure-u-1-1 emptytask" data-taskid="0" droppable="true">&nbsp;</div>
    </div>
</div>
<div class="pure-g">
    <div class="pure-u-1-4 admin">
        <button class="pure-u-1-1 pure-button pure-button-primary" data-admin="new-task"><i class="fa fa-file"></i> New Task</button>
        <button class="pure-u-1-1 pure-button pure-button-primary" data-admin="users"><i class="fa fa-users"></i> Users</button>
        <button class="pure-u-1-1 pure-button pure-button-primary" data-admin="backup"><i class="fa fa-archive"></i> Backup</button>
        <button class="pure-u-1-1 pure-button pure-button-primary" data-admin="config"><i class="fa fa-wrench"></i> Configuration</button>
        <button class="pure-u-1-1 pure-button pure-button-primary" data-admin="log"><i class="fa fa-cogs"></i> Log</button>
    </div>
    <div class="pure-u-3-4">
        @include('newtask')
        @include('users')
        @include('backup')
        @include('config')
        @include('log')
    </div>
</div>
@stop
