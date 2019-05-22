@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex row justify-content-left">
        <div class="text-center mb-2">
            <a class="btn btn-primary" href="{{ route('tasks.create', $workspace) }}">{{ __('Add a task') }}</a>
        </div>
        <div class="col-md-8">
            @forelse ($workspace->tasks as $task)
                <div class="card">
                    <div class="card-header">Workspace name</div>

                    <div class="card-body">
                            <ul>
                                <li>
                                    {{ $task->name }}    
                                </li>    
                            </ul>
                    </div>
                </div>
            <br>
            @empty
                <p>Sorry, there are no tasks created yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
