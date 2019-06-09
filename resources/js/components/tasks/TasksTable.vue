<template>
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
                <tr v-for="task in items">
                    <td v-text="task.name"></td>
                    <td v-text="task.creator.full_name"></td>
                    <td v-text="task.assignee.full_name"></td>
                    <td v-text="task.status"></td>
                    <td v-text="formattedDate(task.start_date)"></td>
                    <td v-text="formattedDate(task.finish_date)"></td>
                </tr>
        </tbody>
    </table>
</template>

<script>
	import moment from 'moment';

	export default {
		props: ['tasks'],
		data() {
			return {
				items: this.tasks,
			}
		},
		methods: {
			formattedDate(date) {
			    return moment(date).format("LL");
			},
		},
	}
</script>