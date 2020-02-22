<template>
    <div class="animated fadeIn">
        <b-row>
            <b-col sm="6" md="3" v-for="(v,i) in list" :key="i">
                <a href="#" class="click" v-on:click.prevent="showDetail(v)">
                    <b-card class="text-white bg-primary">
                        <div class="h4 mb-0">{{v.value}} Pengabdian</div>
                        <small class="text-muted text-uppercase font-weight-bold">{{v.label}}</small>
                        <b-progress class="progress-white progress-xs mt-3" :value="v.percent"/>
                    </b-card>
                </a>
            </b-col>
        </b-row><!--/.row-->
        <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Anggaran</th>
                        <th>Tahun Pelaksanaan</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="item in details" :key="item.penelitian_id">
                    <td>{{item.data.penelitian_judul}}</td>
                    <td>{{item.mod.anggaran}}</td>
                    <td>{{item.data.penelitian_tahun_pelaksanaan}}</td>
                </tr>
                </tbody>
            </table>
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
</template>

<script>
    export default {
        name: 'widgets',
        data() {
            return {
                showModal: false,
                details: '',
                msg: 'Widgets',
                list: '',
                modal_title: "Rincian",
            }
        },
        created() {
            this.getData();
        },

        methods: {
            getData() {
                this.axios.post('/api/dashboard/widget/pengabdian').then(res => {
                    this.list = res.data;
                });
            },
            showDetail(v) {
                this.modal_title = 'Pengabdian ' + v.label;
                var ss_id = v.ss_id;
                this.showModal = true;
                this.axios.get(`/api/pengabdian/detail/${ss_id}`).then(res => {
                    this.details = res.data;
                });
            },
        },
    }
</script>
