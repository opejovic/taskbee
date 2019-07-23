@extends('layouts.app')

@section('content')
<table class="container w-full items-center mx-auto text-left">
    <thead class="border-b-2 border-gray-300">
        <tr class="text-gray-600 text-xs uppercase">
            <th class="py-4 font-normal">Task</th>
            <th class="py-4 font-normal">Creator</th>
            <th class="py-4 font-normal">Person responsible</th>
            <th class="py-4 font-normal">Status</th>
            <th class="py-4 font-normal">Start</th>
            <th class="py-4 font-normal">End</th>
            <th class="font-normal"></th>
		</tr>
	</thead>
    <tbody class="text-left">
        <!-- <tr v-for="task in tasks" :key="task.id"> -->
        <tr class="text-xs uppercase border-b">
            <td class="py-4 font-normal text-gray-700">Task name</td>
            <td class="py-4 font-normal text-gray-700">task.creator.full_name</td>
            <td class="py-4 font-normal text-gray-700">task.assignee.full_name</td>
            <td class="py-4 font-normal text-gray-700">task status vue comp</td>
            <!-- <task-status :task="task" @task-updated="refresh"></task-status> -->
            <td class="py-4 font-normal text-gray-700">task.start_date</td>
            <td class="py-4 font-normal text-gray-700">task.finish_date</td>
            <td
                style="cursor: pointer"
                @click="deleteTask(task)"
                data-toggle="tooltip"
                data-placement="right"
            >
                <i class="material-icons icon">delete</i>
            </td>
        </tr>
    </tbody>
</table>
@endsection