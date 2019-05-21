@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @foreach ($workspace->tasks as $task)
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
            @endforeach

        </div>
    </div>
</div>
@endsection
