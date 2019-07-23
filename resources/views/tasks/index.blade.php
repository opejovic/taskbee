@extends('layouts.app')

@section('content')
    <div>
        <div>
            <tasks-table
                :workspace="{{ $workspace }}"
                :tasks="{{ $tasks }}">
            </tasks-table>
        </div>
    </div>
@endsection
