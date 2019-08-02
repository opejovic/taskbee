@extends('layouts.app')

@section('content')
    <tasks-table
        :workspace="{{ $workspace }}"
        :tasks="{{ $tasks }}">
    </tasks-table>
@endsection
