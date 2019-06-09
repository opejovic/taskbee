<template>
    <div>
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addTaskModal">
            Add task
        </button>

        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add new task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        <form>

                        <div class="form-group">
                            <input type="text" :class="formClass('description')" name="name" id="name" placeholder="Task description" v-model="description" required>

                            <span class="invalid-feedback" role="alert">
                                <strong>Task description is required</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="user_responsible">Person responsible for task completion</label>
                            <select :class="formClass('responsibility')" id="user_responsible" name="user_responsible" v-model="userResponsible">
                                <option v-for="member in members" :value="member.id">
                                    {{ member.first_name }} {{ member.last_name }}
                                </option>
                            </select>

                            <span class="invalid-feedback" role="alert">
                                <strong>Person responsible is required.</strong>
                            </span>
                        </div>

                        <fieldset class="form-group">
                            <label for="start_date">Start date</label>
                            <input type="date" 
                                   name="start_date" 
                                   :class="formClass('start')" 
                                   id="start_date" 
                                   placeholder="Start date"
                                   v-model="startDate">

                            <span class="invalid-feedback" role="alert">
                                <strong>You must provide the start date.</strong>
                            </span>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="finish_date">Finish date</label>
                            <input type="date" 
                                   name="finish_date"
                                   :class="formClass('finish')" 
                                   id="finish_date" 
                                   placeholder="Finish date"
                                   v-model="finishDate">
                            <span class="invalid-feedback" role="alert">
                                <strong>You must provide the finish date.</strong>
                            </span>                                   
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="status">Task status</label>
                            <select :class="formClass('status')" id="status" name="status" v-model="taskStatus" required>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>Task status is required.</strong>
                            </span>                             
                        </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <small>The member responsible will be notified.</small>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="checkForm">Add task</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['workspace'],
        data() {
            return {
                show: true,
                members: this.workspace.members,
                description: null,
                userResponsible: null,
                startDate: null,
                finishDate: null,
                taskStatus: null,
                errors: [],
            }
        },

        methods: {
            formClass(attr) {
                return this.errors.includes(attr)  ? 'form-control is-invalid' : 'form-control';
            },

            checkForm: function(e) {
                this.errors = [];
                if (! this.description) {
                    this.errors.push("description");  
                } 

                if (! this.userResponsible) {
                 this.errors.push("responsibility");   
                }
                
                if (! this.startDate) {
                    this.errors.push("start"); 
                } 
                
                if (! this.finishDate) {
                    this.errors.push("finish");  
                } 

                if (! this.taskStatus) {
                    this.errors.push("status");  
                } 

                console.log(this.errors.includes("Description is required"));
                
                if( ! this.errors.length) return this.addTask();
                e.preventDefault();
            },

            addTask() {
                axios.post(`/workspaces/${this.workspace.id}/tasks`, {
                    name: this.description,
                    user_responsible: this.userResponsible,
                    start_date: this.startDate,
                    finish_date: this.finishDate,
                    status: this.taskStatus,
                }).then(response => {
                    $('#addTaskModal').modal('hide');
                    this.$emit('taskAdded');
                    // flash a message to the user
                }).catch(response => {
                    //
                })
            },

            clear() {
                this.description = null;
                this.userResponsible = null;
                this.startDate = null;
                this.finishDate = null;
                this.taskStatus = null;
                this.errors = [];
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
