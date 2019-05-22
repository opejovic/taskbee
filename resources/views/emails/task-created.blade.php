@component('mail::message')
# Hello, {{ $task->assignee->first_name }},

{{ $task->creator->first_name }} assigned you a new task.

<strong>{{ $task->name }}</strong>

Starting date of the task is <strong>{{ $task->formatted_start_date }}</strong>. It should be finished by <strong>{{ $task->formatted_finish_date }}</strong>

@component('mail::button', ['url' => ''])
Check it out
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
