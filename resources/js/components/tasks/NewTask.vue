<template>
    <div>
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addTaskModal">
            Add task
        </button>

        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add task information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        <form @submit.prevent="addTask">

                            <div class="form-group">
                                <label for="name">Describe the task</label>

                                <input 
                                type="text" 
                                :class="errors.has('name')  ? 'form-control is-invalid' : 'form-control'" 
                                name="name" 
                                id="name" 
                                placeholder="Task description" 
                                v-model="name"
                                @keydown="errors.clear('name')">

                                <span class="invalid-feedback" role="alert" v-if="errors.has('name')">
                                    <strong v-text="errors.get('name')"></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="user_responsible">Who has to complete the task?</label>

                                <select 
                                    :class="errors.has('user_responsible')  ? 'form-control is-invalid' : 'form-control'"
                                    v-model="user_responsible"
                                    @click="errors.clear('user_responsible')">
                                
                                    <option value="" disabled>Choose one...</option>
                                    <option v-for="member in members" :value="member.id">
                                        {{ member.first_name }} {{ member.last_name }}
                                    </option>
                                </select>

                                <span class="invalid-feedback" role="alert" v-if="errors.has('user_responsible')">
                                    <strong v-text="errors.get('user_responsible')"></strong>
                                </span>
                            </div>

                            <fieldset class="form-group">
                                <label for="start_date">Start date</label>
                                <input type="date" 
                                name="start_date" 
                                :class="errors.has('start_date')  ? 'form-control is-invalid' : 'form-control'" 
                                id="start_date" 
                                placeholder="Start date"
                                v-model="start_date"
                                @click="errors.clear('start_date')">

                                <span class="invalid-feedback" role="alert" v-if="errors.has('start_date')">
                                    <strong v-text="errors.get('start_date')"></strong>
                                </span>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="finish_date">Finish date</label>
                                <input type="date" 
                                name="finish_date"
                                :class="errors.has('finish_date')  ? 'form-control is-invalid' : 'form-control'" 
                                id="finish_date" 
                                placeholder="Finish date"
                                v-model="finish_date"
                                @click="errors.clear('finish_date')">

                                <span class="invalid-feedback" role="alert" v-if="errors.has('finish_date')">
                                    <strong v-text="errors.get('finish_date')"></strong>
                                </span>                                   
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="status">Task status</label>
                                <select 
                                :class="errors.has('status')  ? 'form-control is-invalid' : 'form-control'" 
                                id="status" 
                                name="status" 
                                v-model="status"
                                @click="errors.clear('status')">

                                    <option value="" disabled>
                                     Choose one...
                                     </option>
                                     <option value="Planned">
                                         Planned
                                     </option>
                                     <option value="In progress">
                                        In Progress
                                    </option>
                                    <option value="Waiting">
                                        Waiting
                                    </option>
                                    <option value="Testing">
                                        Testing
                                    </option>

                                    <option value="Done">
                                        Done
                                    </option>
                                </select>

                                <span class="invalid-feedback" role="alert" v-if="errors.has('status')">
                                    <strong v-text="errors.get('status')"></strong>
                                </span>                             
                            </fieldset>

                            <div class="modal-footer">
                                <small>The member responsible will be notified.</small>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" :disabled="errors.any()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    class Errors {
        constructor() {
            this.errors = {};
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        any() {
            if (Object.keys(this.errors).length > 0) {
                return true;
            }

            return false;
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors;
        }

        clear(field) {
            if (field) {
                delete this.errors[field];
                
                return;   
            }

            this.errors = {};
        }
    };

    export default {

        props: ['workspace'],
        data() {
            return {
                show: true,
                members: this.workspace.members,
                name: '',
                user_responsible: '',
                start_date: '',
                finish_date: '',
                status: '',
                errors: new Errors(),
            }
        },

        methods: {
            addTask() {
                axios
                .post(`/workspaces/${this.workspace.id}/tasks`, this.$data)
                .then(response => {
                    this.$emit('task-added');
                    $('#addTaskModal').modal('hide');
                    // flash a message to the user
                }).catch(error => { 
                    this.errors.record(error.response.data.errors);
                });
            },

            clear() {
                this.name = '';
                this.user_responsible = '';
                this.start_date = '';
                this.finish_date = '';
                this.status = '';
                this.errors.clear();
            },
        },

        mounted() {
            const self = this;
            $('#addTaskModal').on('hidden.bs.modal', function (e) {
                self.clear();
            });
        },
    };
</script>
