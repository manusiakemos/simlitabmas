<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Survey Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button
                                v-if="user.role == 'user'"
                                class="mb-3 shadow" type="primary" size="small" @click="create"
                                icon="fa fa-plus"> Tambah Survey
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                :url="configDt.url"
                                :columns="configDt.columns"
                                selector="dt-survey"
                                ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->

            <!--modal main-->
            <b-modal :title="modal_title" size="xl" v-model="showModal" @ok="showModal = false">
                <!--nama kegiatan-->
                <b-form-group
                    id="survey_nama_kegiatan-group"
                    label="Nama Kegiatan"
                    label-for="survey_nama_kegiatan"
                    :invalid-feedback="this.errors && this.errors.survey_nama_kegiatan
                    ? this.errors.survey_nama_kegiatan.join()
                    : ''"
                    :state="this.errors && this.errors.survey_nama_kegiatan ? false : true"
                >
                    <b-form-input id="survey_nama_kegiatan"
                                  v-model="data.data.survey_nama_kegiatan"
                    ></b-form-input>
                </b-form-group>
                <!--nama lokasi-->

                <b-form-group
                    id="survey_nama_lokasi-group"
                    label="Nama Lokasi"
                    label-for="survey_nama_lokasi"
                    :invalid-feedback="this.errors && this.errors.survey_nama_lokasi ? this.errors.survey_nama_lokasi.join() : ''"
                    :state="this.errors && this.errors.survey_nama_lokasi ? false : true"
                >
                    <b-form-input id="survey_nama_lokasi"
                                  v-model="data.data.survey_nama_lokasi"
                    ></b-form-input>
                </b-form-group>

                <b-form-checkbox
                    v-if="data.data.status.ss_level == 1 && user.role != 'user'"
                    class="pt-2 pb-4" v-model="data.data.checked_helper" name="check-button" switch>
                    Status <b>({{ data.data.checked_helper ? 'Disetujui' : 'Ditolak' }})</b>
                </b-form-checkbox>

                <!--jika ss level 1 dan diterima-->

                <b-form-group
                    v-if="data.data.checked_helper == true && data.data.status.ss_level == 1"
                    id="survey_tanggal-group"
                    label="Tanggal Survey"
                    label-for="survey_tanggal"
                    :invalid-feedback="this.errors && this.errors.survey_tanggal ? this.errors.survey_tanggal.join() : ''"
                    :state="this.errors && this.errors.survey_tanggal ? false : true"
                >
                    <el-date-picker
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        v-model="data.data.survey_tanggal"
                        type="date"
                        placeholder="Pilih Tanggal survey">
                    </el-date-picker>
                </b-form-group>

                <!--jika ss level 2-->
                <b-form-checkbox
                    v-if="data.data.status.ss_level == 2"
                    class="pt-2 pb-4" v-model="data.data.checked_helper2" name="check-button" switch>
                    Status <b>({{ data.data.checked_helper2 ? 'Finishing' : 'Revisi' }})</b>
                </b-form-checkbox>

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

            <div>
                <!--modal-excel-->
                <b-modal title="Upload Excel" size="xl" v-model="showModalExcel" @ok="showModalExcel = false">
                    <!--jika ss level 3-->
                    <upload v-if="data.data.status.ss_level >= 2 && data.links.upload"
                            title="Upload Excel"
                            :survey="data"
                            variant="excel"
                            :action="data.links.upload"></upload>

                    <template v-slot:modal-footer>
                        <div class="w-100">
                            <div class="d-flex">
                                <b-button
                                    variant="secondary"
                                    class="ml-auto"
                                    @click="showModalExcel=false"
                                >
                                    Tutup
                                </b-button>
                            </div>
                        </div>
                    </template>
                </b-modal>

                <b-modal title="Upload PDF" size="xl" v-model="showModalPDF" @ok="showModalPDF = false">
                    <!--jika ss level 3-->
                    <upload v-if="data.data.status.ss_level >= 3 && data.links.upload"
                            title="Upload PDF"
                            :survey="data"
                            variant="pdf"
                            :action="data.links.upload"></upload>

                    <template v-slot:modal-footer>
                        <div class="w-100">
                            <div class="d-flex">
                                <b-button
                                    variant="secondary"
                                    class="ml-auto"
                                    @click="showModalPDF=false"
                                >
                                    Tutup
                                </b-button>
                            </div>
                        </div>
                    </template>
                </b-modal>
            </div>

        </div>
    </div>
</template>

<script>
    import DataTables from '../../components/base/DataTable';
    import Upload from './Upload';

    export default {
        name: 'surveys',
        components: {
            DataTables, Upload
        },
        computed: {
            user() {
                return this.$store.state.auth.data;
            }
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                showModalExcel: false,
                showModalPDF: false,
                modal_title: 'Tambah Survey',
                data: {
                    "data": {
                        "survey_id": "",
                        "ss_id": "",
                        "survey_nama_lokasi": "",
                        "survey_nama_kegiatan": "",
                        "checked_helper": false,
                        "checked_helper2": false,
                        "survey_tanggal": "",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "status": {
                            "ss_id": "1",
                            "ss_level": "1",
                            "ss_value": "new",
                            "created_at": null,
                            "updated_at": null,
                            "deleted_at": null
                        },
                    },
                    "mod": [],
                    "links": {
                        "store": "/api/survey",
                        "edit": "",
                        "show": "",
                        "update": "",
                        "destroy": "",
                        "upload": ""
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/survey",
                    columns: [
                        {title: "Nama Kegiatan", data: "survey_nama_kegiatan", class: "all"},
                        {title: "Lokasi", data: "survey_nama_lokasi", class: "all"},
                        {title: "Status", data: "status.ss_value", class: "all"},
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
                $(document).find("#dt-survey")
                    .on("click", ".btn-chat", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.$router.push({path: `/chat/${url}`});
                    })
                    .on("click", ".btn-edit", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.edit(url);
                    })
                    .on("click", ".btn-excel", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.edit(url, 'excel');
                    })
                    .on("click", ".btn-pdf", function (e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        vm.edit(url, 'pdf');
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
                this.modal_title = 'Tambah Survey';
                this.showModal = true;
            },
            edit(url, type = 'edit') {
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                    this.modal_title = res.data.data.survey_nama_kegiatan;
                    res.data.data.checked_helper == 1
                        ? this.data.data.checked_helper = true
                        : this.data.data.checked_helper = false;

                    res.data.data.checked_helper2 == 1
                        ? this.data.data.checked_helper2 = true
                        : this.data.data.checked_helper2 = false;
                });
                if (type == 'edit') {
                    this.showModal = true;
                } else if (type == 'excel') {
                    this.showModalExcel = true;
                } else if (type == 'pdf') {
                    this.showModalPDF = true;
                }
            },
            store() {
                this.axios.post('/api/survey', this.data.data).then(res => {
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
