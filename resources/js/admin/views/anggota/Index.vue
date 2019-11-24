<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Anggota Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah Anggota
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                    :url="configDt.url"
                                    :columns="configDt.columns"
                                    selector="dt-anggota"
                                    ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->
            <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
                <!--myform-->
                <b-form-group
                        id="name-group"
                        label="nama"
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
                <!--endform-->
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
    import MyEditor from "../../components/base/MyEditor";

    export default {
        name: 'anggotas',
        components: {
            DataTables,
            MyEditor
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                modal_title: 'Tambah Anggota',
                data: {
                    "data": {
                        "id": "",
                        "name": "",
                        "phone": "",
                        "alamat": "",
                        "created_at": "",
                        "updated_at": "",
                        "deleted_at": null
                    },
                    "mod": [],
                    "links": {
                        "store": "/api/anggota",
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/anggota",
                    columns: [
                        {title: "Nama", data: "name", class: "all"},
                        {title: "Alamat", data: "alamat", class: "all"},
                        {title: "Telepon", data: "phone", class: "all"},
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
                $(document).find("#dt-anggota")
                    .on("click", ".btn-edit", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.edit(url);
                    })
                    .on("click", ".btn-destroy", function (e) {
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
                this.modal_title = 'Tambah Anggota';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit Anggota';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            store() {
                this.axios.post('/api/anggota', this.data.data).then(res => {
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
