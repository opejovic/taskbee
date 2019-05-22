@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create new task</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store', $workspace) }}">
                        @csrf
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Task description">
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="user_responsible">Person responsible for task completion</label>
                            <select class="form-control" id="user_responsible" name="user_responsible">
                                <option>Choose one..</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="start_date">Start date</label>
                            <input type="date" 
                                   name="start_date" 
                                   class="form-control" 
                                   id="start_date" 
                                   placeholder="Start date">
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="finish_date">Finish date</label>
                            <input type="date" 
                                   name="finish_date"
                                   class="form-control" 
                                   id="finish_date" 
                                   placeholder="Finish date">
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="status">Task status</label>
                            <select class="form-control" id="status" name="status">
                                <option>Choose one..</option>
                                <option value="{{ App\Models\Task::PLANNED }}">
                                   Planned
                                </option>
                                <option value="{{ App\Models\Task::IN_PROGRESS }}">
                                    In Progress
                                </option>
                                <option value="{{ App\Models\Task::WAITING }}">
                                    Waiting
                                </option>
                                <option value="{{ App\Models\Task::TESTING }}">
                                    Testing
                                </option>

                                <option value="{{ App\Models\Task::DONE }}">
                                    Done
                                </option>
                            </select>
                        </fieldset>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection