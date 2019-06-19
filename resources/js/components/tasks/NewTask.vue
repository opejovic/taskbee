<template>
    <div>
        <button class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addTaskModal">
                <i class="material-icons add">
                    add
                </i>
                Add task
        </button>

        <div class="modal" 
            id="addTaskModal" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="addTaskModalLabel" 
            aria-hidden="true"
            data-focus="true">

            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add task information</h5>
                        <button type="button" @click="hide" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        <form @submit.prevent="addTask">

                            <div class="form-group">
                                <label for="name">Describe the task</label>

                                <input 
                                    type="text" 
                                    :class="form.errors.has('name')  ? 'form-control is-invalid' : 'form-control'" 
                                    name="name" 
                                    id="name" 
                                    placeholder="Task description" 
                                    v-model="form.name"
                                    @keydown="form.errors.clear('name')">

                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('name')">
                                    <strong v-text="form.errors.get('name')"></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="user_responsible">Who has to complete the task?</label>

                                <select 
                                    :class="form.errors.has('user_responsible')  ? 'form-control is-invalid' : 'form-control'"
                                    v-model="form.user_responsible"
                                    @click="form.errors.clear('user_responsible')">
                                
                                    <option v-for="member in members" :value="member.id">
                                        {{ member.first_name }} {{ member.last_name }}
                                    </option>
                                </select>

                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('user_responsible')">
                                    <strong v-text="form.errors.get('user_responsible')"></strong>
                                </span>
                            </div>

                            <fieldset class="form-group">
                                <label for="start_date">Start date</label>
                                <input type="date" 
                                    name="start_date" 
                                    :class="form.errors.has('start_date')  ? 'form-control is-invalid' : 'form-control'" 
                                    id="start_date" 
                                    placeholder="Start date"
                                    v-model="form.start_date"
                                    @click="form.errors.clear('start_date')">

                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('start_date')">
                                    <strong v-text="form.errors.get('start_date')"></strong>
                                </span>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="finish_date">Finish date</label>
                                <input type="date" 
                                    name="finish_date"
                                    :class="form.errors.has('finish_date')  ? 'form-control is-invalid' : 'form-control'" 
                                    id="finish_date" 
                                    placeholder="Finish date"
                                    v-model="form.finish_date"
                                    @click="form.errors.clear('finish_date')">

                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('finish_date')">
                                    <strong v-text="form.errors.get('finish_date')"></strong>
                                </span>                                   
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="status">Task status</label>
                                <select 
                                    :class="form.errors.has('status')  ? 'form-control is-invalid' : 'form-control'" 
                                    id="status" 
                                    name="status" 
                                    v-model="form.status"
                                    @click="form.errors.clear('status')">

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

                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('status')">
                                    <strong v-text="form.errors.get('status')"></strong>
                                </span>                             
                            </fieldset>

                            <div class="modal-footer">
                                <small>The member responsible will be notified.</small>
                                <button type="button" class="btn btn-secondary" @click="hide">Close</button>
                                <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">Submit</button>
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

    class Form {
        constructor(data) {
            this.originalData = data;

            for (let field in data) {
                this[field] = data[field];
            }

            this.errors = new Errors();
        }

        data() {
            var data = {};

            for (let property in this.originalData) {
                data[property] = this[property];
            }

            return data;
        }

        post(url) {
            return this.submit('post', url);
        }

        submit(requestType, url) {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.data())
                    .then(response => {
                        this.onSuccess(response.data);

                        resolve(response.data);
                    }).catch(error => { 
                        this.onFail(error.response.data.errors);

                        reject(error.response.data.errors);
                    });
            });
        }

        onSuccess() {
            this.reset();
        }

        onFail(errors) {
            this.errors.record(errors);
        }

        reset() {
            for (let field in this.originalData) {
                this[field] = null;
            }

            this.errors.clear();
        }
    }

    export default {
        props: ['workspace'],
        data() {
            return {
                form: new Form({
                    name: null,
                    user_responsible: null,
                    start_date: null,
                    finish_date: null,
                    status: null,
                }),
                
                members: this.workspace.members,
            }
        },

        methods: {
            addTask() {
                this.form.post(`/workspaces/${this.workspace.id}/tasks`)
                    .then(response => {
                        window.events.$emit('task-added');
                        $('#addTaskModal').modal('hide');
                        
                        // flash a message to the user
                        this.$toasted.show('Task created!');
                    });
            },

            hide() {
                $('#addTaskModal').modal('hide');
                this.form.reset();
            }
        },
    };
</script>

<style>
    .add {
        vertical-align: middle;
    }
</style>