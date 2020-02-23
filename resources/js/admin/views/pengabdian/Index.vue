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
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="showModalPrint = true"
                                       icon="fa fa-print"> Cetak Pengabdian
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
                            id="penelitian_tanggal-group"
                            label="Tanggal"
                            label-for="penelitian_tanggal"
                            :invalid-feedback="this.errors && this.errors.penelitian_tanggal ? this.errors.penelitian_tanggal.join() : ''"
                            :state="this.errors && this.errors.penelitian_tanggal ? false : true"
                    >
                        <my-date-picker id="penelitian_tanggal"
                                        v-model="data.data.penelitian_tanggal"
                        ></my-date-picker>
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
                        <currency-input
                                class="form-control"
                                v-model="data.data.penelitian_anggaran"
                        />
                    </b-form-group>

                    <b-form-group
                            id="pengabdian_tempat-group"
                            label="tempat"
                            label-for="pengabdian_tempat"
                            :invalid-feedback="this.errors && this.errors.pengabdian_tempat ? this.errors.pengabdian_tempat.join() : ''"
                            :state="this.errors && this.errors.pengabdian_tempat ? false : true"
                    >
                        <b-form-input id="pengabdian_tempat"
                                      v-model="data.data.pengabdian_tempat"
                        ></b-form-input>
                    </b-form-group>

                    <!--tambah anggota-->
                    <b-form-group
                            v-for="(value, index) in anggota_id" :key="index"
                            label="anggota"
                            :id="`anggota-group-${index}`"
                            :label-for="`anggota-group-${index}`">
                        <b-row>
                            <b-col cols="9">
                                <el-select class="w-100"
                                           v-model="anggota_id[index]">
                                    <el-option v-for="item in anggota"
                                               :key="item.name"
                                               :disabled="anggota_id.includes(item.id)"
                                               :label="item.name"
                                               :value="item.id">
                                    </el-option>
                                </el-select>
                            </b-col>
                            <b-col cols="3">
                                <div class="float-right">
                                    <el-button size="medium" type="primary" @click="addAnggotaId"><span
                                            class="fa fa-plus"></span></el-button>
                                    <el-button size="medium" type="danger"
                                               v-if="anggota_id.length > 1"
                                               @click="removeAnggotaId(index)">
                                        <span class="fa fa-minus"></span></el-button>
                                </div>
                            </b-col>
                        </b-row>
                    </b-form-group>
                    <!--endform-->
                </div>
                <div v-else-if="user.role == 'admin'">
                    <b-form-group label="Status">
                        <b-form-radio v-model="acceptData" name="some-radios" :value="true">Diterima</b-form-radio>
                        <b-form-radio v-model="acceptData" name="some-radios" :value="false">Ditolak</b-form-radio>
                    </b-form-group>
                    <!--  <b-form-checkbox v-model="acceptData" name="check-button" switch>
                          Status Pengabdian <b>({{acceptData ? 'Diterima' : 'Ditolak'}})</b>
                      </b-form-checkbox>-->

                    <b-form-group v-if="acceptData == false"
                                  id="penelitian_alasan_ditolak-group"
                                  label="alasan ditolak"
                                  label-for="penelitian_alasan_ditolak"
                                  :invalid-feedback="this.errors && this.errors.penelitian_alasan_ditolak ? this.errors.penelitian_alasan_ditolak.join() : ''"
                                  :state="this.errors && this.errors.penelitian_alasan_ditolak ? false : true"
                    >
                        <b-form-textarea
                                id="textarea"
                                v-model="data.data.penelitian_alasan_ditolak"
                                placeholder="..."
                                rows="3"
                                max-rows="6"
                        ></b-form-textarea>
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
            <b-modal title="Cetak Pengabdian Pertahun" size="sm" v-model="showModalPrint" @ok="showModalPrint = false">
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
                <h4 class="text-center mb-3">Pengabdian Tahun {{ year }}</h4>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th v-for="(v, i) in configDt.columns" :key="i" v-if="v.printable">{{v.title}}</th>
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
    import MyDatePicker from "../../components/base/MyDatePicker.vue";
    import MyYearPicker from "../../components/base/MyYearPicker.vue";

    import Upload from "./Upload";
    import Widgets from "./Widgets";

    export default {
        name: 'penelitians',
        components: {
            Widgets,
            MyEditor,
            DataTables,
            MyMoney,
            MyDatePicker,
            MyYearPicker,
            Upload
        },
        data() {
            return {
                action: 'store',
                loading: false,
                showModal: false,
                showModalUpload: false,
                showModalPrint: false,
                modal_title: 'Tambah Pengabdian',
                uploadUrl: null,
                year: d.getFullYear(),
                anggota_id: ["",],
                anggota: [],
                data: {
                    "data": {
                        "penelitian_id": "",
                        "user_id": "",
                        "penelitian_anggaran": 0,
                        "penelitian_judul": "",
                        "penelitian_tanggal": "",
                        "penelitian_ringkasan": "",
                        "penelitian_tahun_pelaksanaan": "",
                        "penelitian_alasan_ditolak": "",
                        "pengabdian_tempat" : "",
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
                errors: [],
                configDt: {
                    url: "/api/pengabdian",
                    columns: [
                        {title: "Pengabdian ", data: "penelitian_judul", class: "all", printable: true},
                        {title: "Anggaran", data: "penelitian_anggaran", class: "auto", printable: true},
                        {
                            title: "Tanggal",
                            data: "penelitian_tanggal",
                            class: "auto",
                            printable: true
                        },
                        {title: "Tempat", data: "pengabdian_tempat", class: "none", printable: true},
                        {title: "Ringkasan", data: "penelitian_ringkasan", class: "none", printable: true},
                        {
                            title: "Alasan(Jika Ditolak)",
                            data: "penelitian_alasan_ditolak",
                            class: "none",
                            printable: false
                        },
                        {title: "Status", data: "status.ss_value", class: "auto", printable: true},
                        {title: "Action", data: "action", class: "text-center w-25 all", printable: false}
                    ]
                },
                list: [],
                showSelect: false,
            }
        },
        created() {
            this.data2 = this.data;
            this.getData();
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
                        vm.$router.push({path: `detail/pengabdian/${id}`});
                        // vm.$router.push({path: `/pengabdian/anggota/${id}`});
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
                this.anggota_id = [""];
                this.data = _.cloneDeep(this.data2);
                this.action = 'store';
                this.modal_title = 'Tambah Pengabdian';
                this.showModal = true;
            },
            edit(url) {
                this.anggota_id = [];
                this.showSelect = false;
                this.modal_title = 'Edit Pengabdian ';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                    res.data.data.penelitian_anggota.forEach(v=>{
                        this.anggota_id.push(v.anggota_id);
                    });
                });
                this.showSelect = true;
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
                this.axios.post('/api/pengabdian', {
                    penelitian_judul: this.data.data.penelitian_judul,
                    penelitian_anggaran: this.data.data.penelitian_anggaran,
                    penelitian_ringkasan: this.data.data.penelitian_ringkasan,
                    penelitian_tanggal: this.data.data.penelitian_tanggal,
                    pengabdian_tempat: this.data.data.pengabdian_tempat,
                    anggota_id: this.anggota_id
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
            update() {
                this.axios.put(this.data.links.update, {
                    penelitian_judul: this.data.data.penelitian_judul,
                    penelitian_anggaran: this.data.data.penelitian_anggaran,
                    penelitian_ringkasan: this.data.data.penelitian_ringkasan,
                    penelitian_tanggal: this.data.data.penelitian_tanggal,
                    pengabdian_tempat: this.data.data.pengabdian_tempat,
                    anggota_id: this.anggota_id
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
            updateStatusAjax() {
                this.axios.put(this.data.links.update, {
                    'ss_level': this.data.data.status.ss_level,
                    'penelitian_alasan_ditolak': this.data.data.penelitian_alasan_ditolak
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
                    this.uploadUrl = `/api/pengabdian/${id}/upload`;

                });
                this.showModalUpload = true;
            },
            print() {
                this.loading = true;
                this.axios.get(`/api/pengabdian?filter_tahun_cetak=${this.year}`).then(res => {
                    this.list = res.data;
                    if (this.list.length > 0) {
                        this.$nextTick(() => {
                            this.showModalPrint = false;
                            this.loading = false;
                            var selector = 'printMe';
                            this.$htmlToPaper(selector);
                        });
                    } else {
                        this.loading = false;
                        this.$message('data kosong');
                    }
                });
            },
            getData() {
                this.showSelect = false;
                this.axios.post('/api/select-options/anggota', {
                    anggota: this.anggota_id
                }).then(res => {
                    this.anggota = res.data;
                    this.showSelect = true;
                });
            },
            addAnggotaId() {
                this.anggota_id.push("");
                // this.getData()
            },
            removeAnggotaId(index) {
                this.anggota_id.splice(index, 1);
                // this.getData();
            },
        },
        computed: {
            user() {
                return this.$store.state.auth.data;
            },
            setRefresh() {
                return this.$store.state.refresh;
            },
            acceptData: {
                get: function () {
                    if (this.data.data.status.ss_level <= 1) {
                        return false;
                    } else if (this.data.data.status.ss_level == 2) {
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
