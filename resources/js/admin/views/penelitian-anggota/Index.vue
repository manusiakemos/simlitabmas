<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Penelitian</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">Anggota</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <b-row>
                    <b-col cols="12">
                        <div class="mt-3">
                            <b-card>
                                <ul class="list-group" v-if="list.length > 0">
                                    <li v-for="(v,i) in list" :key="i"
                                        class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <p> <strong>{{v.data.anggota.name}}</strong> </p>
                                            <small> {{v.data.anggota.phone}}</small>
                                            <small v-html="v.data.anggota.alamat"></small>
                                        </div>
                                        <div v-if="user.role == 'user'">
                                            <button class="btn btn-danger" @click="destroy(v)"><span class="fa fa-trash"></span></button>
                                        </div>
                                    </li>
                                </ul>
                                <h4 class="text-center" v-else>Belum ada anggota yang ditambahkan</h4>
                            </b-card>
                        </div>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col cols="12">
                        <div class="mt-3">
                            <b-card>
                                <data-tables
                                        :url="configDt.url"
                                        :columns="configDt.columns"
                                        selector="dt-pilih-anggota"
                                        ref="dt">
                                </data-tables>
                            </b-card>
                        </div>
                    </b-col>
                </b-row>
            </div>
        </div>
    </div>
</template>

<script>
    import DataTables from '../../components/base/DataTable';

    export default {
        components: {
            DataTables
        },
        created() {
            this.getData();
        },
        mounted() {
            this.setDt();
        },
        data() {
            return {
                penelitian_id: this.$route.params.penelitian_id,
                list: [],
                data: {
                    "data": {
                        "pa_id": "",
                        "anggota_id": "",
                        "penelitian_id": "",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "anggota": {
                            "id": "",
                            "is_anggota": "",
                            "jabatan": "",
                            "golongan": "",
                            "name": "",
                            "nip": "",
                            "username": "",
                            "email": "",
                            "api_token": "",
                            "avatar": null,
                            "phone": "",
                            "role": "",
                            "email_verified_at": null,
                            "alamat": "",
                        },
                        "penelitian": {
                            "penelitian_id": "",
                            "user_id": "",
                            "ss_id": "",
                            "penelitian_judul": "",
                            "penelitian_ringkasan": "",
                            "penelitian_anggaran": "",
                            "penelitian_tahun_pelaksanaan": "",
                        }
                    },
                    "mod": [],
                    "links": {
                        "store": "/api/penelitiananggota",
                    }
                },
                configDt: {
                    url: "/api/penelitiananggota",
                    columns: [
                        {title: "Nama", data: "name", class: "all"},
                        {title: "Alamat", data: "alamat", class: "none"},
                        {title: "Telp", data: "phone", class: "auto"},
                        {title: "Action", data: "action", class: "text-center w-25 all"}
                    ]
                },
            }
        },
        methods: {
            getData() {
                var id = this.penelitian_id;
                this.axios.get(`/api/penelitiananggota/${id}`).then(res => {
                    this.list = res.data
                });
            },
            setDt() {
                var ini = this;
                $(document).find("#dt-pilih-anggota")
                    .on("click", ".btn-select", function (e) {
                        e.preventDefault();
                        var id = $(this).data('id');
                        ini.addAnggota(id);
                    });
            },
            addAnggota(id) {
                this.axios.post('api/penelitiananggota', {
                    anggota_id: id,
                    penelitian_id: this.penelitian_id
                }).then(res => {

                    if (res.status) {
                        this.$message({
                            message: res.data.message,
                            type: 'success'
                        });

                        this.getData();
                    }
                }).catch(err => {
                    this.$message({
                        message: err.response.data.errors.anggota_id.join(),
                        type: 'error'
                    });
                });
            },
            destroy(v) {
                var url = v.links.destroy;
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
                            this.getData();
                        }
                    })
                })
            },
        },
        computed: {
            user() {
                return this.$store.state.auth.data;
            }
        },

    }
</script>
