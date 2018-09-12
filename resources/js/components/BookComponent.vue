<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="false"
            :is-full-page="true"></loading>
        <h3>Books <button type="button" v-b-modal.modal-book class="btn btn-primary float-right"><font-awesome-icon icon="plus"></font-awesome-icon> Add</button></h3>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-sm-6 col-md-3">
                                <input type="text" class="form-control" placeholder="Search" v-model="search">
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Available</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="book of pagination.data" :key="book.id">
                                    <td>{{ book.name }}</td>
                                    <td>{{ book.category.name }}</td>
                                    <td>{{ book.author }}</td>
                                    <td>{{ book.user ? `No (${book.user})` : 'Yes' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info" v-on:click="updateStatus(book)"><font-awesome-icon icon="plus"></font-awesome-icon> Status</button>
                                        <button type="button" class="btn btn-primary" v-on:click="updateForm(book)"><font-awesome-icon icon="edit"></font-awesome-icon> Edit</button>
                                        <button type="button" class="btn btn-danger" v-on:click="remove(book)"><font-awesome-icon icon="trash"></font-awesome-icon> Remove</button>
                                    </td>
                                </tr>
                                <tr v-show="pagination.total === 0">
                                    <td colspan="5" class="text-center">Any result</td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :data="pagination" @pagination-change-page="getData"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="modal-book" ref="modal_book" :title="title_modal" @ok="save" @shown="cancel" ok-title="Save">
            <div class="alert alert-danger" v-show="hasError">
                {{ errorMessage }}
            </div>
            <form>
                <div class="mb-2">
                    <label>Name:</label>
                    <input type="text" name="name" v-model="book.name" data-vv-scope="book" placeholder="Name" class="form-control" v-bind:class="{ 'is-invalid': errors.has('book.name') }" v-validate="'required'">
                    <div class="invalid-feedback" v-show="errors.has('book.name')">
                        {{ errors.first('book.name') }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Author:</label>
                    <input type="text" name="author" v-model="book.author" data-vv-scope="book" placeholder="Author" class="form-control" v-bind:class="{ 'is-invalid': errors.has('book.author') }"  v-validate="'required'">
                    <div class="invalid-feedback" v-show="errors.has('book.author')">
                        {{ errors.first('book.author') }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Category:</label>
                    <vue-select v-model="book.category" name="category" data-vv-scope="book" placeholder="Select one choice" v-bind:class="{ 'is-invalid': errors.has('book.category') }"  v-validate="'required'" :options="categories"></vue-select>
                    <div class="invalid-feedback" v-show="errors.has('book.category')">
                        {{ errors.first('book.category') }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Published Date:</label>
                    <date-picker v-model="book.published_date" lang="en" name="published_date" data-vv-scope="book" :first-day-of-week="1" :not-after="new Date()" v-validate="'required'" v-bind:class="{ 'is-invalid': errors.has('book.published_date') }" ></date-picker>
                    <div class="invalid-feedback" v-show="errors.has('book.published_date')">
                        {{ errors.first('book.published_date') }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>User:</label>
                    <input type="text" v-model="book.user" name="user" data-vv-scope="book" placeholder="User" class="form-control">
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import VueSelect from 'vue-select';
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.min.css';

    export default {
        components: {
            DatePicker,
            VueSelect,
            Loading
        },
        data() {
            return {
                isLoading: false,
                hasError: false,
                title_modal: 'Add Book',
                errorMessage: '',
                pagination: {
                    total: 0,
                    data: [],
                },
                categories: [],
                book: this.fields(),
                search: ''
            }
        },
        mounted() {
            this.getData();
            this.$http({
                url: 'category',
                method: 'get'
            }).then(response => {
                let _categories = [];
                response.data.forEach(element => {
                    _categories.push({ label: element.name, value: element.id });
                });
                this.categories = _categories;
            }).catch(error => {
                console.log(error);
            });
        },
        watch: {
            search: function (val) {
                this.getData(1);
            }
        },
        methods: {
            cancel() {
                this.title_modal = 'Add book';
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
                let url = 'book';
                let method = 'post';
                if (this.book.id) {
                    method = 'put';
                    url = `book/${this.book.id}`;
                }
                this.$http({
                    url,
                    method,
                    data: this.book
                }).then(response => {
                    this.getData(this.pagination.current_page);
                    this.isLoading = false;
                    this.$refs.modal_book.hide();
                    this.book = this.fields();
                    this.title_modal = 'Add book';
                }).catch(error => {
                    this.isLoading = false;
                    this.hasError = true;
                    this.errorMessage = error.response.data.message;
                });
            },
            fields() {
                return {
                    name: '',
                    category: '',
                    author: '',
                    published_date: '',
                    user: ''
                };
            },
            updateForm (book) {
                this.title_modal = 'Edit book';
                this.book = Object.assign({}, book);
                this.book.category = {
                    label: book.category.name,
                    value: book.category.id
                };
                this.$refs.modal_book.show();
            },
            updateStatus(book) {
                if (book.user) {
                    this.$swal({
                        title: 'Mark as available?',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes!',
                        cancelButtonText: 'No'
                    }).then(result => {
                        if (result.value) {
                            this.$swal.showLoading();
                            let data = {
                                available: true
                            };
                            return this.sendRequest(book.id, data);
                        } else {
                            return null;
                        }
                    }).then(response => {
                        if (response) {
                            this.getData(this.pagination.current_page);
                            this.$swal('Book available', '', 'success');
                        }
                    });
                } else {
                    this.$swal({
                        title: 'User name',
                        input: 'text',
                        inputValue: '',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            return !value && 'You need to write user name!'
                        }
                    }).then(result => {
                        let data = {
                            user: result.value
                        };
                        return this.sendRequest(book.id, data);
                    }).then(response => {
                        this.getData(this.pagination.current_page);
                        this.$swal('Book borrowed', '', 'success');
                    });
                }
            },
            sendRequest(bookId, data) {
                return this.$http({
                    method: 'put',
                    url: `book/toggle/${bookId}`,
                    data: data
                });
            },
            remove(book) {
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this book!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then(result => {
                    if (result.value) {
                        this.$swal.showLoading();
                        this.$http({
                            method: 'delete',
                            url: `book/${book.id}`
                        }).then(response => {
                            this.getData();
                            this.$swal(
                                'Deleted!',
                                'The book has been deleted.',
                                'success'
                            );
                        }).catch(error => {
                            this.$swal(
                                'Deleted!',
                                error.response.data.message,
                                'error'
                            );
                        });
                    }
                })
            },
            getData(page = 1) {
                this.$http({
                    url: 'book',
                    method: 'get',
                    params: {
                        page,
                        term: this.search
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
