@if (!$overdue->isEmpty())
There are overdue tasks that need completing:

@foreach ($overdue as $task)
- {{ $task->description }}

@endforeach

There are also tasks that are due very soon:

@endif
@foreach ($due_soon as $task)
- {{ $task->description }}

@endforeach

View all tasks:
{{ Config::get('app.url') }}/tasks
