<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">{{type}}</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">Detail</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0"  v-if="data">
            <div class="animated fadeIn border-0">
                <b-row>
                    <b-col cols="12">
                        <b-card>
                            <b-card-body>
                                <h4 class="text-capitalize mb-3">{{data.data.penelitian_judul}}</h4>
                                <!--<pre>{{data}}</pre>-->
                                <table class="table">
                                    <tr>
                                        <td>Tahun</td>
                                        <td><span v-html="data.data.penelitian_tahun"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td><span v-html="data.mod.tanggal"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Ringkasan</td>
                                        <td><span v-html="data.data.penelitian_judul"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td>{{data.mod.anggaran}}</td>
                                    </tr>
                                    <tr v-if="data.data.is_pengabdian ==  1">
                                        <td>Tempat</td>
                                        <td>{{data.data.pengabdian_tempat}}</td>
                                    </tr>
                                    <tr v-if="data.data.status.ss_level == 0">
                                        <td>Alasan ditolak</td>
                                        <td>{{data.data.penelitian_alasan_ditolak}}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <span class="badge badge-danger p-2" v-if="data.data.status.ss_level == 0">
                                                {{data.data.status.ss_value}}
                                            </span>
                                            <span class="badge badge-primary p-2"
                                                  v-else-if="data.data.status.ss_level == 1">
                                                {{data.data.status.ss_value}}
                                            </span>
                                            <span class="badge badge-success p-2"
                                                  v-else-if="data.data.status.ss_level > 1">
                                                {{data.data.status.ss_value}}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                               <div class="mt-3">
                                   <h4>Anggota</h4>
                                   <table class="table">
                                       <thead>
                                       <tr>
                                           <th>Nama</th>
                                           <th>NIP</th>
                                           <th>Jabatan</th>
                                           <!--<th>Golongan</th>-->
                                           <th>Alamat</th>
                                           <th>Telepon</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr v-for="(item, index) in data.data.penelitian_anggota" :key="index">
                                           <td>{{ item.anggota.name }}</td>
                                           <td>{{ item.anggota.nip }}</td>
                                           <td>{{ item.anggota.jabatan }}</td>
                                           <td><span v-html="item.anggota.alamat"></span></td>
                                           <td>{{ item.anggota.phone }}</td>
                                       </tr>
                                       </tbody>
                                   </table>
                               </div>
                            </b-card-body>
                            <button v-if="data.data.status.ss_level == 1 && user.role == 'admin'" class="ml-auto btn btn-primary" @click="updateStatus">Ubah Status</button>
                            <button v-if="data.data.status.ss_level == 1 && user.role == 'user'" class="ml-auto btn btn-primary" @click="edit">Edit</button>
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
                          Status Penelitian <b>({{acceptData ? 'Diterima' : 'Ditolak'}})</b>
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

    export default {
        components: {
            MyEditor,
            DataTables,
            MyMoney,
            MyDatePicker,
            MyYearPicker,
        },
        created() {
            this.getData();
        },
        data() {
            return {
                penelitian_id: this.$route.params.penelitian_id,
                type: this.$route.params.type,
                data: '',
                errors: null,
                action: 'store',
                loading: false,
                showModal: false,
                showModalUpload: false,
                showModalPrint: false,
                modal_title: 'Tambah Pengabdian',
                uploadUrl: null,
                year: d.getFullYear(),
                anggota_id: [],
                anggota: [],
            }
        },
        methods: {
            addAnggotaId() {
                this.anggota_id.push("");
                // this.getData()
            },
            removeAnggotaId(index) {
                this.anggota_id.splice(index, 1);
                // this.getData();
            },
            getData() {
                var id = this.penelitian_id;
                this.axios.get('/api/select-options/anggota').then(res => {
                    this.anggota = res.data;
                });
                var vm = this;
                this.axios.get(`/api/penelitian/${id}/edit`).then(res => {
                    this.data = res.data;
                    res.data.data.penelitian_anggota.forEach(v => {
                        vm.anggota_id.push(v.anggota_id);
                    });
                    console.log(vm.anggota_id);
                })
            },
            edit() {
                this.modal_title = 'Edit Penelitian';
                this.action = 'update';
                this.showModal = true;
            },
            updateStatus() {
                this.modal_title = 'Ubah Status';
                this.action = 'ubah-status';
                this.showModal = true;
            },
            update() {
                this.axios.put(this.data.links.update, {
                    penelitian_judul: this.data.data.penelitian_judul,
                    penelitian_anggaran: this.data.data.penelitian_anggaran,
                    penelitian_ringkasan: this.data.data.penelitian_ringkasan,
                    penelitian_tanggal: this.data.data.penelitian_tanggal,
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
            refreshDt() {
                this.getData();
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
        watch:{
            penelitian_id(){
                this.getData();
            }
        }

    }
</script>
