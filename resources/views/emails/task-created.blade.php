@component('mail::message')
# Hello, {{ $task->assignee->first_name }}

I have assigned you a new task.

Name: <strong>{{ $task->name }}</strong>

Starting date of the task is <strong>{{ $task->formatted_start_date }}</strong><br>
It should be finished by <strong>{{ $task->formatted_finish_date }}</strong>

@component('mail::button', ['url' => ''])
Check it out
@endcomponent
Thanks,<br>
{{ $task->creator->first_name }}<br>
<small><strong>{{ $task->workspace->name }}</strong></small>
@endcomponent
