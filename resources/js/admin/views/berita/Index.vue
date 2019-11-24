<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Berita Datatable</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper">
            <div class="animated fadeIn">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="create"
                                       icon="fa fa-plus"> Tambah Berita
                            </el-button>
                            <el-button class="mb-3 shadow" type="primary" size="small" @click="refreshDt"
                                       icon="fa fa-refresh"> Refresh
                            </el-button>

                            <data-tables
                                :url="configDt.url"
                                :columns="configDt.columns"
                                selector="dt-berita"
                                ref="dt">
                            </data-tables>
                        </b-card>
                    </b-col>
                </b-row>
            </div>

            <!-- Modal Component -->
            <b-modal :title="modal_title" size="xl" v-model="showModal" @ok="showModal = false">
                <!--judul-->
                <b-form-group
                    id="berita_judul-group"
                    label="Berita Judul"
                    label-for="berita_judul"
                    :invalid-feedback="this.errors && this.errors.berita_judul ? this.errors.berita_judul.join() : ''"
                    :state="this.errors && this.errors.berita_judul ? false : true"
                >
                    <b-form-input id="berita_judul"
                                  v-model="data.data.berita_judul"
                    ></b-form-input>
                </b-form-group>

                <!--kategori-->
                <b-form-group
                    id="bk_id-group"
                    label="Kategori"
                    label-for="bk_id"
                    :invalid-feedback="this.errors && this.errors.bk_id ? this.errors.bk_id.join() : ''"
                    :state="this.errors && this.errors.bk_id ? false : true"
                >
                    <select-kategori id="bk_id"
                                     v-model="data.data.bk_id"
                    ></select-kategori>
                </b-form-group>
                <!--berita_aktif-->
                <b-form-group
                    id="berita_aktif-group"
                    label=""
                    label-for="berita_aktif"
                    :invalid-feedback="this.errors && this.errors.berita_aktif ? this.errors.berita_aktif.join() : ''"
                    :state="this.errors && this.errors.berita_aktif ? false : true"
                >
                    <b-form-checkbox v-model="data.data.berita_aktif" name="check-button" switch>
                        Publish
                    </b-form-checkbox>
                </b-form-group>
                <!--custom_url-->
                <b-form-group
                    id="custom_url-group"
                    label=""
                    label-for="custom_url"
                    :invalid-feedback="this.errors && this.errors.custom_url ? this.errors.custom_url.join() : ''"
                    :state="this.errors && this.errors.custom_url ? false : true"
                >
                    <b-form-checkbox v-model="data.data.custom_url" name="check-button" switch>
                        Custom URL
                    </b-form-checkbox>
                </b-form-group>

                <!--berita_url-->
                <b-form-group
                    id="berita_url-group"
                    label="Berita URL"
                    label-for="berita_url"
                    :invalid-feedback="this.errors && this.errors.berita_url ? this.errors.berita_url.join() : ''"
                    :state="this.errors && this.errors.berita_url ? false : true"
                >
                    <b-form-input id="berita_url"
                                  v-if="data.data.custom_url == true"
                                  :readonly="false"
                                  v-model="data.data.berita_url"
                    ></b-form-input>
                    <b-form-input id="berita_url"
                                  v-else-if="data.data.custom_url == false"
                                  :readonly="true"
                                  v-model="kebabJudul"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    id="berita_isi-group"
                    label="Isi Berita"
                    label-for="berita_isi"
                    :invalid-feedback="this.errors && this.errors.berita_isi ? this.errors.berita_isi.join() : ''"
                    :state="this.errors && this.errors.berita_isi ? false : true"
                >
                   <my-editor v-model="data.data.berita_isi"></my-editor>
                </b-form-group>
                <!--upload-->
                <div class="d-flex align-items-center justify-content-center">
                    <upload ref="uploadImageRef" v-model="data.data.berita_gambar"></upload>
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
    import MyEditor from '../../components/base/MyEditor';
    import SelectKategori from './SelectBeritaKategori';
    import Upload from './Upload';

    export default {
        name: 'berita',
        components: {
            DataTables, SelectKategori, Upload, MyEditor
        },
        data() {
            return {
                action: 'store',
                showModal: false,
                modal_title: 'Tambah Berita',
                data: {
                    data: {
                        "berita_id": "",
                        "user_id": "",
                        "bk_id": "",
                        "berita_aktif": "",
                        "berita_judul": "",
                        "berita_url": "",
                        "berita_gambar": "",
                        "berita_isi": "",
                        "berita_hit": 0,
                        "custom_url": 0,
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null
                    },
                    links: {
                        store: "/api/berita",
                        update: "",
                        destroy: ""
                    }
                },
                data2: null,
                errors: '',
                configDt: {
                    url: "/api/berita",
                    columns: [
                        {title: "Judul", data: "berita_judul", class: "all"},
                        {title: "Action", data: "action", class: "text-center w-25 all"}
                    ]
                },
                actionUpload: "/api/upload",
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
                $(document).find("#dt-berita").on("click", ".btn-edit", function (e) {
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
                this.modal_title = 'Tambah Berita';
                this.showModal = true;
            },
            edit(url) {
                this.modal_title = 'Edit Berita';
                this.action = 'update';
                this.axios.get(url).then(res => {
                    this.data = _.cloneDeep(res.data);
                });
                this.showModal = true;
            },
            store() {
                this.axios.post('/api/berita', this.data.data).then(res => {
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
            },
        },
        watch: {
            showModal: function (value) {
                value == false ? this.errors = [] : '';
            },
            kebabJudul:function (value) {
                this.data.data.berita_url = value;
            }
        },
        computed: {
            kebabJudul() {
                var d = new Date();
                return d.getFullYear() + '/' + d.getMonth() + '/' + _.kebabCase(this.data.data.berita_judul);
            },
        },
    }
</script>
