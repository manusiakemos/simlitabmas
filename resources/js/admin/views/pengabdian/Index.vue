<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Pengabdian Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <widgets ref="widgetRef"></widgets>
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button v-if="user.role == 'user'"
                                    class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah Pengabdian
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                    :url="configDt.url"
                                    :columns="configDt.columns"
                                    selector="dt-pengabdian"
                                    ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->
            <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
               <!--user role-->
                <div v-if="user.role == 'user'">
                    <!--my form-->
                    <b-form-group
                            id="penelitian_judul-group"
                            label="judul"
                            label-for="penelitian_judul"
                            :invalid-feedback="this.errors && this.errors.penelitian_judul ? this.errors.penelitian_judul.join() : ''"
                            :state="this.errors && this.errors.penelitian_judul ? false : true"
                    >
                        <b-form-input id="penelitian_judul"
                                      v-model="data.data.penelitian_judul"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="pengabdian_tempat-group"
                            label="Tempat"
                            label-for="pengabdian_tempat"
                            :invalid-feedback="this.errors && this.errors.pengabdian_tempat ? this.errors.pengabdian_tempat.join() : ''"
                            :state="this.errors && this.errors.pengabdian_tempat ? false : true"
                    >
                        <b-form-input id="pengabdian_tempat"
                                      v-model="data.data.pengabdian_tempat"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="penelitian_tahun_pelaksanaan-group"
                            label="Tahun pelaksanaan"
                            label-for="penelitian_tahun_pelaksanaan"
                            :invalid-feedback="this.errors && this.errors.penelitian_tahun_pelaksanaan ? this.errors.penelitian_tahun_pelaksanaan.join() : ''"
                            :state="this.errors && this.errors.penelitian_tahun_pelaksanaan ? false : true"
                    >
                        <my-year-picker id="penelitian_tahun_pelaksanaan"
                                        v-model="data.data.penelitian_tahun_pelaksanaan"
                        ></my-year-picker>
                    </b-form-group>

                    <b-form-group
                            id="penelitian_ringkasan-group"
                            label="ringkasan"
                            label-for="penelitian_ringkasan"
                            :invalid-feedback="this.errors && this.errors.penelitian_ringkasan ? this.errors.penelitian_ringkasan.join() : ''"
                            :state="this.errors && this.errors.penelitian_ringkasan ? false : true"
                    >
                        <my-editor id="penelitian_ringkasan"
                                   v-model="data.data.penelitian_ringkasan"
                        ></my-editor>
                    </b-form-group>

                    <b-form-group
                            id="penelitian_anggaran-group"
                            label="anggaran"
                            label-for="penelitian_anggaran"
                            :invalid-feedback="this.errors && this.errors.penelitian_anggaran ? this.errors.penelitian_anggaran.join() : ''"
                            :state="this.errors && this.errors.penelitian_anggaran ? false : true"
                    >
                        <my-money id="penelitian_anggaran"
                                  v-model="data.data.penelitian_anggaran"
                        ></my-money>
                    </b-form-group>
                    <!--endform-->
                </div>
                <div v-else-if="user.role == 'admin'">
                    <b-form-checkbox v-model="acceptData" name="check-button" switch>
                        Status Pengabdian <b>({{acceptData ? 'Diterima' : 'Ditolak'}})</b>
                    </b-form-checkbox>
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
                            <b-button v-else-if="action == 'update'"
                                      variant="primary"
                                      class="ml-1"
                                      @click="update"
                            >
                                Simpan
                            </b-button>
                            <b-button v-else
                                      variant="primary"
                                      class="ml-1"
                                      @click="updateStatusAjax"
                            >
                                Simpan
                            </b-button>
                        </div>
                    </div>
                </template>
            </b-modal>

            <!--Modal Upload Component-->
            <b-modal title="File Proposal" size="lg" v-model="showModalUpload" @ok="showModalUpload = false">
                <!--my form-->
                <upload v-if="showModalUpload && uploadUrl"
                        title="Upload File"
                        :action="uploadUrl"
                        :penelitian="data"
                        variant="all"></upload>
                <!--endform-->
                <template v-slot:modal-footer>
                    <div class="w-100">
                        <div class="d-flex">
                            <b-button
                                    variant="secondary"
                                    class="ml-auto"
                                    @click="showModalUpload=false">
                                Tutup
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
    import MyMoney from "../../components/base/MyMoney";
    import MyYearPicker from "../../components/base/MyYearPicker";

    import Upload from "./Upload";
    import Widgets from "./Widget";

    export default {
        name: 'penelitians',
        components: {
            Widgets,
            MyEditor,
            DataTables,
            MyMoney,
            MyYearPicker,
            Upload
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                showModalUpload: false,
                modal_title: 'Tambah Pengabdian',
                uploadUrl: null,
                data: {
                    "data": {
                        "penelitian_id": "",
                        "user_id": "",
                        "penelitian_anggaran": "",
                        "penelitian_judul": "",
                        "penelitian_ringkasan": "",
                        "penelitian_tahun_pelaksanaan": "",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "status": {
                            "ss_id": 2,
                            "ss_level": 1,
                            "ss_value": "baru",
                            "created_at": null,
                            "updated_at": null
                        }
                    },
                    "mod": [],
                    "links": {
                        "store": "/api/pengabdian",
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/pengabdian",
                    columns: [
                        {title: "Pengabdian", data: "penelitian_judul", class: "all"},
                        {title: "Anggaran", data: "penelitian_anggaran", class: "auto"},
                        {title: "Tahun Pelaksanaan", data: "penelitian_tahun_pelaksanaan", class: "auto"},
                        {title: "Status", data: "status.ss_value", class: "auto"},
                        {title: "Ringkasan", data: "penelitian_ringkasan", class: "none"},
                        {title: "Tempat", data: "pengabdian_tempat", class: "none"},
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
                $(document).find("#dt-pengabdian")
                    .on("click", ".btn-upload", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.upload(url);
                    })
                    .on("click", ".btn-status", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.updateStatus(url);
                    })
                    .on("click", ".btn-anggota", function (e) {
                        e.preventDefault();
                        var id = $(this).data('id');
                        vm.$router.push({path:`/pengabdian/anggota/${id}`});
                    })
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
                this.modal_title = 'Tambah Pengabdian';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit Pengabdian';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            updateStatus(url) {
                this.modal_title = 'Ubah Status';
                this.action = 'ubah-status';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            store() {
                this.axios.post('/api/pengabdian', this.data.data).then(res => {
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
            updateStatusAjax() {
                this.axios.put(this.data.links.update, {
                    'ss_level' : this.data.data.status.ss_level
                }).then(res => {
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
            },
            upload(url) {
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                    var id = _.cloneDeep(res.data.data.penelitian_id);
                    this.uploadUrl = `/api/penelitian/${id}/upload`;

                });
                this.showModalUpload = true;
            },
        },
        computed:{
            user(){
                return this.$store.state.auth.data;
            },
            setRefresh(){
              return this.$store.state.refresh;
            },
            acceptData:{
                get: function () {
                    if (this.data.data.status.ss_level <= 1) {
                        return false;
                    } else if(this.data.data.status.ss_level == 2){
                        return true;
                    }
                },
                // setter
                set: function (newValue) {
                    if (newValue == true) {
                        this.data.data.status.ss_level = 2;
                    } else {
                        this.data.data.status.ss_level = 0;
                    }
                }
            }
        },
        watch: {
            showModal: function (value) {
                value == false ? this.errors = [] : '';
            },
            showModalUpload: function (value) {
                value == false ? this.uploadUrl = null : '';
            },
            setRefresh: function (value) {
                this.refreshDt();
                this.$refs.widgetRef.getData();
            }
        }
    }
</script>
