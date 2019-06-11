@extends('layouts.app')

@section('content')
<div class="container">
    <div>
        <tasks-table :workspace="{{ $workspace }}"></tasks-table>
    </div>
</div>
@endsection