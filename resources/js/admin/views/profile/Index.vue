<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <el-breadcrumb separator="/" class="mb-3 mt-3 text-capitalize">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Profile</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <div>
                    <b-card class="shadow-sm">
                        <div class="d-lg-flex d-lg-inline-block">
                            <div class="d-block mb-2 p-4 d-flex justify-content-center">
                                <el-upload v-if="user"
                                           class="avatar-uploader"
                                           :action="user.links.avatar"
                                           :headers="headers"
                                           :show-file-list="false"
                                           :on-success="refreshData"
                                           :before-upload="beforeAvatarUpload">
                                    <img v-if="userData.avatar" :src="userData.avatar" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </div>

                            <div class="d-block ml-3 w-100 pr-4">
                                <b-form-group
                                    id="username-group"
                                    label="Username"
                                    label-for="username"
                                    :invalid-feedback="this.errors && this.errors.username ? this.errors.username.join() : ''"
                                    :state="this.errors && this.errors.username ? false : true"
                                >
                                    <b-form-input id="username"
                                                  v-model="user.username"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="name-group"
                                    label="Name"
                                    label-for="name"
                                    :invalid-feedback="this.errors && this.errors.name ? this.errors.name.join() : ''"
                                    :state="this.errors && this.errors.name ? false : true"
                                >
                                    <b-form-input id="name"
                                                  v-model="user.name"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="phone-group"
                                    label="Phone"
                                    label-for="phone"
                                    :invalid-feedback="this.errors && this.errors.phone ? this.errors.phone.join() : ''"
                                    :state="this.errors && this.errors.phone ? false : true"
                                >
                                    <b-form-input id="phone"
                                                  v-model="user.phone"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="email-group"
                                    label="Email"
                                    label-for="email"
                                    :invalid-feedback="this.errors && this.errors.email ? this.errors.email.join() : ''"
                                    :state="this.errors && this.errors.email ? false : true"
                                >
                                    <b-form-input id="email"
                                                  type="email"
                                                  v-model="user.email"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="password-group"
                                    label="Password"
                                    label-for="password"
                                    :invalid-feedback="this.errors && this.errors.password ? this.errors.password.join() : ''"
                                    :state="this.errors && this.errors.password ? false : true"
                                >
                                    <b-form-input id="password"
                                                  type="password"
                                                  autocomplete="new-password"
                                                  v-model="user.password"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="password_confirmation-group"
                                    label="Password Confirmation"
                                    label-for="password_confirmation"
                                    :invalid-feedback="this.errors && this.errors.password_confirmation ? this.errors.password_confirmation.join() : ''"
                                    :state="this.errors && this.errors.password_confirmation ? false : true"
                                >
                                    <b-form-input id="password_confirmation"
                                                  type="password"
                                                  v-model="user.password_confirmation"
                                    ></b-form-input>
                                </b-form-group>

                                <div class="mt-5">
                                    <el-button
                                        @click="updateProfile"
                                        class="float-right"
                                        type="primary" size="medium" :loading="loading">Update Profile
                                    </el-button>
                                </div>
                            </div>
                        </div>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            userData() {
                return this.$store.state.auth.data;
            }
        },
        mounted() {
            this.user = this.userData
        },
        methods: {
            updateProfile() {
                this.loading = true;
                this.axios.put(this.user.links.update, this.user).then(res => {
                    var d = res.data.data;
                    if (res.data.status) {
                        this.$store.commit("setAuthData", d);
                        this.loading = false;
                        this.errors = [];
                        this.$message({
                            type: res.data.text,
                            message: res.data.message
                        });
                    }
                }).catch(err => {
                    this.errors = err.response.data.errors;
                });
                this.loading = false;
            },
            refreshData() {
                this.axios.get(this.user.links.edit).then(res => {
                    var d = res.data;
                    if (res.data) {
                        this.$store.commit("setAuthData", d);
                        this.$message({
                            type: 'success',
                            message: 'Avatar berhasil diupdate'
                        });
                    }
                });
            },
            beforeAvatarUpload(file) {
                // const isJPG = file.type === 'image/jpeg';
                // const isLt2M = file.size / 1024 / 1024 < 2;
                //
                // if (!isJPG) {
                //     this.$message.error('Avatar picture must be JPG format!');
                // }
                // if (!isLt2M) {
                //     this.$message.error('Avatar picture size can not exceed 2MB!');
                // }
                // return isJPG && isLt2M;
            }
        },
        data() {
            return {
                user: '',
                errors: null,
                loading: false,
                modal_title: "Change Avatar",
                showModal: false,
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.auth.data.api_token
                },
            }
        },
    }
</script>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        width: calc(80vw / 4) !important;
        height: calc(80vw / 4) !important;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: calc(80vw / 4) !important;
        height: calc(80vw / 4) !important;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: calc(80vw / 4) !important;
        height: calc(80vw / 4) !important;
        display: block;
    }
</style>
