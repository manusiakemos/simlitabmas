<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Users Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card class="shadow-sm">
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah User
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                    :url="configDt.url"
                                    :columns="configDt.columns"
                                    selector="dt-user"
                                    ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->
            <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
                <b-form-group
                        id="username-group"
                        label="Username"
                        label-for="username"
                        :invalid-feedback="this.errors && this.errors.username ? this.errors.username.join() : ''"
                        :state="this.errors && this.errors.username ? false : true"
                >
                    <b-form-input id="username"
                                  autocomplete="username"
                                  v-model="data.data.username"
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
                                  autocomplete="new-password"
                                  type="password"
                                  v-model="data.data.password"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        id="password-confirmation-group"
                        label="Password Confirm"
                        label-for="password-confirmation"
                        :invalid-feedback="this.errors && this.errors.password_confirmation ? this.errors.password_confirmation.join() : ''"
                        :state="this.errors && this.errors.password_confirmation ? false : true"
                >
                    <b-form-input id="password-confirmation" type="password"
                                  v-model="data.data.password_confirmation"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        id="role-group"
                        label="Role"
                        label-for="role"
                        :invalid-feedback="this.errors && this.errors.role ? this.errors.role.join() : ''"
                        :state="this.errors && this.errors.role ? false : true"
                >
                    <select-role id="role"
                                 v-model="data.data.role"
                    ></select-role>
                </b-form-group>

                <div v-if="data.data.role != 'super-admin' && data.data.role">
                    <b-form-group
                            id="name-group"
                            label="name"
                            label-for="name"
                            :invalid-feedback="this.errors && this.errors.name ? this.errors.name.join() : ''"
                            :state="this.errors && this.errors.name ? false : true"
                    >
                        <b-form-input id="name"
                                      autocomplete="name"
                                      v-model="data.data.name"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="nip-group"
                            label="nip"
                            label-for="nip"
                            :invalid-feedback="this.errors && this.errors.nip ? this.errors.nip.join() : ''"
                            :state="this.errors && this.errors.nip ? false : true"
                    >
                        <b-form-input id="nip"
                                      autocomplete="nip"
                                      v-model="data.data.nip"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="jabatan-group"
                            label="jabatan"
                            label-for="jabatan"
                            :invalid-feedback="this.errors && this.errors.jabatan ? this.errors.jabatan.join() : ''"
                            :state="this.errors && this.errors.jabatan ? false : true"
                    >
                        <b-form-input id="jabatan"
                                      autocomplete="jabatan"
                                      v-model="data.data.jabatan"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="golongan-group"
                            label="golongan"
                            label-for="golongan"
                            :invalid-feedback="this.errors && this.errors.golongan ? this.errors.golongan.join() : ''"
                            :state="this.errors && this.errors.golongan ? false : true"
                    >
                        <b-form-input id="golongan"
                                      autocomplete="golongan"
                                      v-model="data.data.golongan"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="email-group"
                            label="email"
                            label-for="email"
                            :invalid-feedback="this.errors && this.errors.email ? this.errors.email.join() : ''"
                            :state="this.errors && this.errors.email ? false : true"
                    >
                        <b-form-input id="email"
                                      autocomplete="email"
                                      v-model="data.data.email"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="phone-group"
                            label="telepon"
                            label-for="phone"
                            :invalid-feedback="this.errors && this.errors.phone ? this.errors.phone.join() : ''"
                            :state="this.errors && this.errors.phone ? false : true"
                    >
                        <b-form-input id="phone"
                                      autocomplete="phone"
                                      v-model="data.data.phone"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="alamat-group"
                            label="Alamat"
                            label-for="alamat"
                            :invalid-feedback="this.errors && this.errors.alamat ? this.errors.alamat.join() : ''"
                            :state="this.errors && this.errors.alamat ? false : true"
                    >
                        <my-editor id="alamat"
                                   autocomplete="alamat"
                                   v-model="data.data.alamat"
                        ></my-editor>
                    </b-form-group>
                </div>


                <template v-slot:modal-footer>
                    <div class="w-100">
                        <div class="d-flex">
                            <b-button
                                    variant="secondary"
                                    class="ml-auto"
                                    @click="showModal=false"
                            >
                                Tutup
                            </b-button>
                            <b-button v-if="action == 'store'"
                                      variant="primary"
                                      class="ml-1"
                                      @click="store"
                            >
                                Simpan
                            </b-button>
                            <b-button v-else
                                      variant="primary"
                                      class="ml-1"
                                      @click="update"
                            >
                                Simpan
                            </b-button>
                        </div>
                    </div>
                </template>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import DataTables from '../../components/base/DataTable';
    import SelectRole from '../../components/base/SelectRole';
    import MyEditor from "../../components/base/MyEditor";
    // import SelectGolongan from './SelectGolongan';
    // import SelectJabatan from './SelectJabatan';

    export default {
        name: 'users',
        components: {
            DataTables, SelectRole, MyEditor
            // SelectGolongan, 
            // SelectJabatan
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                modal_title: 'Tambah User',
                data: {
                    data: {
                        "id": "",
                        "jabatan": null,
                        "golongan": null,
                        "name": "",
                        "email": "",
                        "nip": "",
                        "username": "",
                        "api_token": "",
                        "avatar": "",
                        "phone": "",
                        "role": "",
                        "email_verified_at": null,
                        "alamat": null,
                        "created_at": null,
                        "updated_at": "",
                        "deleted_at": null
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
                        {title: "Name", data: "name", class: "all"},
                        {title: "Jabatan", data: "jabatan", class: "auto"},
                        {title: "Golongan", data: "golongan", class: "auto"},
                        {title: "NIP", data: "nip", class: "auto"},
                        {title: "Telp", data: "phone", class: "auto"},
                        {title: "Email", data: "email", class: "auto"},
                        {title: "Action", data: "action", class: "text-center w-25 all"}
                    ]
                },
            }
        },
        created() {
            this.data2 = this.data;
        },
        mounted() {
            this.setDt();
        },
        methods: {
            setDt() {
                var vm = this;
                $(document).find("#dt-user").on("click", ".btn-edit", function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    vm.edit(url);
                }).on("click", ".btn-destroy", function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    vm.destroy(url);
                })
            },
            refreshDt() {
                this.$refs.dt.refresh();
            },
            create() {
                this.data = _.cloneDeep(this.data2);
                this.action = 'store';
                this.modal_title = 'Tambah User';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit User';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            store() {
                this.axios.post('/api/user', this.data.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: res.data.text
                        });
                        this.showModal = false;
                        this.refreshDt();
                    }
                }).catch(error => {
                    if (error.response) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
            update() {
                this.axios.put(this.data.links.update, this.data.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: res.data.text
                        });
                        this.showModal = false;
                        this.refreshDt();
                    }
                }).catch(error => {
                    if (error.response) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
            destroy(url) {
                this.$confirm('Apakah Kamu Yakin?', 'Warning', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    type: 'warning'
                }).then(() => {
                    this.axios.delete(url).then(res => {
                        this.$message({
                            type: res.data.text,
                            message: res.data.message
                        });
                        if (res.data.status) {
                            this.refreshDt();
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Data batal dihapus'
                    });
                    this.refreshDt();
                });
            }
        },
        watch: {
            showModal: function (value) {
                value == false ? this.errors = [] : '';
            }
        }
    }
</script>
