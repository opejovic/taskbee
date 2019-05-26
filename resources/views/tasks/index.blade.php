@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex row justify-content-between">
        <div class="text-center mb-2">
            <a class="btn btn-primary btn-lg" href="{{ route('tasks.create', $workspace) }}">
                {{ __('Add task') }}
            </a>
        </div>

        <table class="table col-md-10">
            <thead>
                <tr>
                    <th scope="col">Task</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Person responsible</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start date</th>
                    <th scope="col">Finish date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->creator->full_name }}</td>
                        <td>{{ $task->assignee->full_name }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->formatted_start_date }}</td>
                        <td>{{ $task->formatted_finish_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Sorry, there are no tasks created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
