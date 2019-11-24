<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Sliders</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <b-row>
                    <b-col cols="12">
                        <div class="mt-3">
                            <my-slider-upload class="shadow-sm"></my-slider-upload>
                        </div>
                    </b-col>
                </b-row>
            </div>
        </div>
    </div>
</template>

<script>
    import MySliderUpload from './MySliderUpload';

    export default {
        name: 'users',
        components: {
            MySliderUpload
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                modal_title: 'Tambah User',
                data: {
                    data: {
                        password: "",
                        password_confirmation: "",
                        username: "",
                        role: "",
                    },
                    links: {
                        store: "/api/user",
                        update: "",
                        destroy: ""
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/user",
                    columns: [
                        {title: "Username", data: "username", class: "all"},
                        {title: "Role", data: "role", class: "auto"},
                        {title: "Action", data: "action", class: "text-center w-25 all"}
                    ]
                },
            }
        },
        created() {
            this.data2 = this.data;
        },
        watch: {
            showModal: function (value) {
                value == false ? this.errors = [] : '';
            }
        }
    }
</script>
