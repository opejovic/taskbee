@extends('layouts.app')

@section('content')
<div class="container">
    <div>
        <tasks-table :workspace="{{ $workspace }}" :tasks="{{ $tasks }}"></tasks-table>
    </div>
</div>
@endsection