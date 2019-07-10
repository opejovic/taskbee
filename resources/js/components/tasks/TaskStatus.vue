<template>
    <td class="dropdown cursor">
        <span
            id="navbarDropdown"
            class="dropdown-toggle badge"
            :class="classes"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            v-text="task.status"
        >
            <span class="caret"></span>
        </span>

        <div
            class="dropdown-menu dropdown-menu-right"
            aria-labelledby="navbarDropdown"
        >
            <a
                class="dropdown-item"
                v-for="taskStatus in statuses"
                :key="taskStatus.id"
                v-text="taskStatus"
                @click="updateStatus(taskStatus)"
            ></a>
        </div>
    </td>
</template>

<script>
export default {
    props: ["task"],
    data() {
        return {
            statuses: ["Planned", "In progress", "Waiting", "Testing", "Done"]
        };
    },

    computed: {
        classes() {
            switch (this.task.status) {
                case "Planned":
                    return "planned";
                    break;
                case "In progress":
                    return "inprogress";
                    break;
                case "Waiting":
                    return "badge-warning";
                    break;
                case "Testing":
                    return "testing";
                    break;
                case "Done":
                    return "done";
                    break;
                default:
                    return;
            }
        }
    },

    methods: {
        updateStatus(taskStatus) {
            if (this.task.status == taskStatus) {
                return;
            }

            axios
                .patch(
                    `/workspaces/${this.task.workspace_id}/tasks/${
                        this.task.id
                    }`,
                    {
                        status: taskStatus
                    }
                )
                .then(response => {
                    this.$emit("task-updated");
                    this.$toasted.show("Task updated!");
                })
                .catch(error => {
                    this.checkError(error.response.data.message);
                });
        },

        checkError(message) {
            if (message == "Too Many Attempts.") {
                this.$toasted.show(
                    "You can update up to 10 tasks per minute. Try again shortly."
                );
            }
        }
    }
};
</script>

<style>
.cursor {
    cursor: pointer;
}

.done {
    background-color: #00b365;
    color: white;
}

.planned {
    background-color: #6200ee;
    color: white;
}

.testing {
    background-color: #dae0e5;
}

.inprogress {
    background-color: #f06900;
    color: white;
}
</style>
