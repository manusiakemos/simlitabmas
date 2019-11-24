<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Penelitian Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <widgets ref="widgetRef"></widgets>
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button v-if="user.role == 'user'"
                                    class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah Penelitian
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="showModalPrint = true"
                                       icon="fa fa-print"> Cetak Penelitian
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                    :url="configDt.url"
                                    :columns="configDt.columns"
                                    selector="dt-penelitian"
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
                        Status Penelitian <b>({{acceptData ? 'Diterima' : 'Ditolak'}})</b>
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

            <!--modal-print-->
            <b-modal title="Cetak Penelitian Pertahun" size="sm" v-model="showModalPrint" @ok="showModalPrint = false">
                <my-year-picker v-model="year"></my-year-picker>
                <div class="text-center">
                    <b-spinner variant="primary" label="Spinning" v-if="loading"></b-spinner>
                </div>
                <template v-slot:modal-footer>
                    <div class="w-100">
                        <div class="d-flex">
                            <b-button
                                    variant="secondary"
                                    class="ml-auto"
                                    @click="showModalPrint=false">
                                Tutup
                            </b-button>
                            <b-button
                                    variant="primary"
                                    class="ml-1"
                                    @click="print">
                                Cetak
                            </b-button>
                        </div>
                    </div>
                </template>
            </b-modal>

            <!--print element-->
            <div id="printMe" class="bg-white p-4 d-none d-print-block" v-show="list.length > 0">
                <h4 class="text-center mb-3">Penelitian Tahun {{ year }}</h4>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th  v-for="(v, i) in configDt.columns" :key="i" v-if="v.printable">{{v.title}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in list" :key="key">
                        <td v-for="(v, i) in configDt.columns" :key="i" v-if="v.printable">
                            <span v-if="v.title == 'Status'">{{ item.status ? item.status.ss_value : ''}}</span>
                            <span v-else v-html="item[v.data]"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    var d = new Date();
    import DataTables from '../../components/base/DataTable';
    import MyEditor from "../../components/base/MyEditor";
    import MyMoney from "../../components/base/MyMoney";
    import MyYearPicker from "../../components/base/MyYearPicker";

    import Upload from "./Upload";
    import Widgets from "./Widgets";

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
                loading:false,
                showModal: false,
                showModalUpload: false,
                showModalPrint: false,
                modal_title: 'Tambah Penelitian',
                uploadUrl: null,
                year: d.getFullYear(),
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
                        "store": "/api/penelitian",
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/penelitian",
                    columns: [
                        {title: "Penelitian", data: "penelitian_judul", class: "all", printable:true},
                        {title: "Anggaran", data: "penelitian_anggaran", class: "auto", printable:true},
                        {title: "Tahun Pelaksanaan", data: "penelitian_tahun_pelaksanaan", class: "auto", printable:true},
                        {title: "Status", data: "status.ss_value", class: "auto", printable:true},
                        {title: "Ringkasan", data: "penelitian_ringkasan", class: "none", printable:true},
                        {title: "Action", data: "action", class: "text-center w-25 all", printable:false}
                    ]
                },
                list:[]
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
                $(document).find("#dt-penelitian")
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
                        vm.$router.push({path:`/penelitian/anggota/${id}`});
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
                this.modal_title = 'Tambah Penelitian';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit Penelitian';
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
                this.axios.post('/api/penelitian', this.data.data).then(res => {
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
            print() {
                this.loading = true;
                this.axios.get(`/api/penelitian?filter_tahun_cetak=${this.year}`).then(res=>{
                    this.list = res.data;
                    if(this.list.length > 0){
                        this.$nextTick(()=>{
                            this.showModalPrint = false;
                            this.loading = false;
                            var selector = 'printMe';
                            this.$htmlToPaper(selector);
                        });
                    }else{
                        this.loading = false;
                        this.$message('data kosong');
                    }
                });
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
