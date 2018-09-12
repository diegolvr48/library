<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="false"
            :is-full-page="true"></loading>
        <h3>Categories <button type="button" v-b-modal.modal-category class="btn btn-primary float-right"><font-awesome-icon icon="plus"></font-awesome-icon> Add</Button></h3>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Books</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in pagination.data" :key="category.id">
                                    <td>{{ category.name }}</td>
                                    <td>{{ category.description }}</td>
                                    <td>
                                        {{ category.books.map(book => book.name).join(', ') }}
                                    </td>
                                </tr>
                                <tr v-show="pagination.total === 0">
                                    <td colspan="3" class="text-center">Any result</td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :data="pagination" @pagination-change-page="getData"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="modal-category" ref="modal_category" title="Add Category" @ok="save" @shown="cancel" ok-title="Save">
            <div class="alert alert-danger" v-show="hasError">
                {{ errorsMessage }}
            </div>
            <form>
                <div class="mb-2">
                    <label>Name:</label>
                    <input type="text" name="name" v-model="category.name" data-vv-scope="category" placeholder="Name" class="form-control" v-bind:class="{ 'is-invalid': errors.has('category.name') }" v-validate="'required'">
                    <div class="invalid-feedback" v-show="errors.has('category.name')">
                        {{ errors.first('category.name') }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Description:</label>
                    <textarea name="description" class="form-control" v-model="category.description" data-vv-scope="category" v-bind:class="{ 'is-invalid': errors.has('category.description') }"  v-validate="'required'"></textarea>
                    <div class="invalid-feedback" v-show="errors.has('category.description')">
                        {{ errors.first('category.description') }}
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.min.css';

    export default {
        components: {
            Loading
        },
        data() {
            return {
                isLoading: false,
                hasError: false,
                errorsMessage: '',
                pagination: {
                    data: [],
                    total: 0
                },
                category: {
                    name: '',
                    description: ''
                }
            };
        },
        mounted() {
            this.getData();
        },
        methods: {
            cancel() {
                
            },
            save (evt) {
                evt.preventDefault();

                this.$validator.validate()
                .then(result => {
                    if (result) {
                        this.submit()
                    }
                });
            },
            submit () {
                this.isLoading = true;
                this.$http({
                    method: 'post',
                    url: 'category',
                    data: this.category
                }).then(response => {
                    this.$refs.modal_category.hide();
                   this.getData(this.pagination.current_page);
                    this.isLoading = false;
                }).catch(error => {
                    this.hasError = true;
                    this.errorsMessage = error.response.data.message;
                    this.isLoading = false;
                });
            },
            getData(page=1) {
                this.$http({
                    url: 'category',
                    method: 'get',
                    params: {
                        page
                    }
                }).then(response => {
                    this.pagination = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
