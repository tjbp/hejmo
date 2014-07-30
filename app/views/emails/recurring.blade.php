@if (count($tasks) > 1)
The following tasks are due today:
@else
The following task is due today:
@endif

@foreach ($tasks as $task)
- {{ $task->description }}

@endforeach

View all tasks:
{{ Config::get('app.url') }}/tasks
