<template>
    <div class="app flex-row align-items-center w-100" id="loginVue">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="8">
                    <b-card-group>
                        <b-card no-body class="p-4 bg-secondary text-primary">
                            <b-card-body>
                                <b-form @submit="auth">
                                    <h1>{{$store.state.config.app_name}}</h1>
                                    <p class="text-dark">Sign In to your account</p>
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend>
                                            <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                                        </b-input-group-prepend>
                                        <b-form-input type="text"
                                                      class="form-control"
                                                      placeholder="Username"
                                                      v-model="data.username"
                                                      autocomplete="username email"/>
                                    </b-input-group>
                                    <b-input-group class="mb-4">
                                        <b-input-group-prepend>
                                            <b-input-group-text><i class="icon-lock"></i></b-input-group-text>
                                        </b-input-group-prepend>
                                        <b-form-input type="password"
                                                      class="form-control"
                                                      placeholder="Password"
                                                      v-model="data.password"
                                                      autocomplete="new-password"/>
                                    </b-input-group>
                                    <b-row>
                                        <b-col cols="6">
                                            <b-button variant="primary" class="px-4 text-white" @click="auth">Login</b-button>
                                        </b-col>
                                        <!-- <b-col cols="6" class="text-right">
                                           <b-button variant="link" class="px-0">Forgot password?</b-button>
                                         </b-col>-->
                                    </b-row>
                                </b-form>
                            </b-card-body>
                        </b-card>
                        <!--
                                    <b-card no-body class="text-white bg-primary py-5 d-md-down-none" style="width:44%">
                                      <b-card-body class="text-center">
                                        <div>
                                          <h2>Sign up</h2>
                                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                          <b-button variant="primary" class="active mt-3">Register Now!</b-button>
                                        </div>
                                      </b-card-body>
                                    </b-card>
                        -->
                    </b-card-group>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Login',
        data() {
            return {
                data: {
                    username: "",
                    password: ""
                }
            }
        },
        methods: {
            auth(evt) {
                evt.preventDefault();
                this.$http.post('/api/login', this.data).then(res => {
                    if (res.status == 200) {
                        if (res.data.data) {
                            var d = res.data.data;
                            if (d.api_token) {
                                this.$store.commit("setAuth", res.data);
                                this.$store.commit("resetRefresh");

                                this.$http.defaults.headers.common["Authorization"] = `Bearer ${d.api_token}`;
                                $.ajaxSetup({
                                    headers: {"Authorization": `Bearer ${d.api_token}`}
                                });

                                if(d.role == 'user'){
                                    this.$router.push({path: "/dashboard"});
                                }else{
                                    this.$router.push({path: "/dashboard"});
                                }
                            } else {
                                alert("Username atau password salah")
                            }
                        }
                    }
                }).catch(err => {
                    this.$message({
                        message: err.response.data.errors.username.join(),
                        type: 'danger'
                    })
                });
            }
        }
    }
</script>

<style scoped>
    #loginVue {
        background-color: #171e4c;
        background-image: url("data:image/svg+xml,%3Csvg width='42' height='44' viewBox='0 0 42 44' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='Page-1' fill='none' fill-rule='evenodd'%3E%3Cg id='brick-wall' fill='%23fde672' fill-opacity='0.4'%3E%3Cpath d='M0 0h42v44H0V0zm1 1h40v20H1V1zM0 23h20v20H0V23zm22 0h20v20H22V23z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>
