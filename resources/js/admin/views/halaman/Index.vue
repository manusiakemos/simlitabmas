<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Halaman Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah Halaman
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                :url="configDt.url"
                                :columns="configDt.columns"
                                selector="dt-halaman"
                                ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->
            <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
                <!--body-->

                <!--judul-->
                <b-form-group
                    id="hal_judul-group"
                    label="Judul"
                    label-for="hal_judul"
                    :invalid-feedback="this.errors && this.errors.hal_judul ? this.errors.hal_judul.join() : ''"
                    :state="this.errors && this.errors.hal_judul ? false : true"
                >
                    <b-form-input id="hal_judul"
                                  v-model="data.data.hal_judul"
                    ></b-form-input>
                </b-form-group>
                <!--custom url-->
                <b-form-group
                    id="hal_custom-group"
                    label=""
                    label-for="hal_custom"
                    :invalid-feedback="this.errors && this.errors.hal_custom ? this.errors.hal_custom.join() : ''"
                    :state="this.errors && this.errors.hal_custom ? false : true"
                >
                    <b-form-checkbox v-model="data.data.hal_custom" name="check-button" switch>
                        Custom URL
                    </b-form-checkbox>
                </b-form-group>

                <!--url-->
                <b-form-group
                    v-if="data.data.hal_custom"
                    id="hal_url-group"
                    label="URL"
                    label-for="hal_url"
                    :invalid-feedback="this.errors && this.errors.hal_url ? this.errors.hal_url.join() : ''"
                    :state="this.errors && this.errors.hal_url ? false : true"
                >
                    <b-form-input id="hal_url"
                                  v-model="data.data.hal_url"
                    ></b-form-input>
                </b-form-group>
                <!--footer-->
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

    export default {
        name: 'halaman',
        components: {
            DataTables, SelectRole
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                modal_title: 'Tambah Halaman',
                data: {
                    "data": {
                        "hal_id": "",
                        "hal_url": "",
                        "hal_judul": "",
                        "hal_isi": "",
                        "hal_aktif": 0,
                        "hal_custom": 0,
                        "created_at": "",
                        "updated_at": ""
                    },
                    "mod": [],
                    "links": {
                        "store": "/api/halaman",
                        "edit": "",
                        "show": "",
                        "update": "",
                        "destroy": ""
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/halaman",
                    columns: [
                        {title: "Judul", data: "hal_judul", class: "all"},
                        {title: "URL", data: "hal_url", class: "auto"},
                        {title: "Status", data: "hal_aktif", class: "auto"},
                        // {title: "Custom", data: "hal_custom", class: "auto"},
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
                $(document).find("#dt-halaman").on("click", ".btn-edit", function (e) {
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
                this.modal_title = 'Tambah Halaman';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit Halaman';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            store() {
                this.axios.post('/api/halaman', this.data.data).then(res => {
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
