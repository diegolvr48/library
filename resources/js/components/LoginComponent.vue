<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="error">
                            <p>There was an error, unable to sign in with those credentials.</p>
                        </div>
                        <form autocomplete="off" @submit.prevent="login" method="post">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control" placeholder="admin@admin.com" v-model="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" v-model="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" v-bind:disabled="isLoading" class="btn btn-primary">{{ isLoading ? 'Loading...' : 'Sign in' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        email: null,
        password: null,
        error: false,
        isLoading: false
      }
    },
    methods: {
      login(){
        var app = this;
        this.isLoading = true;
        this.$auth.login({
            body: {},
            data: {
              email: app.email,
              password: app.password
            },
            redirect: '/'
        }).then((response) => {
            this.isLoading = false;
        }).catch((err) => {
            this.isLoading = false;
            this.error = true;
        });       
      },
    }
  } 
</script>