<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <div class="row">
            <b-col>
                <b-card class="shadow-sm">
                    <el-button
                        @click="create"
                        type="primary" class="mb-3" icon="fa fa-plus" size="small"> Tambah
                    </el-button>
                    <el-button
                        @click="refresh"
                        type="primary" class="mb-3" icon="fa fa-refresh" size="small"> Refresh
                    </el-button>
                    <nested ref="nestedRef" class="col-12" v-model="children"/>
                </b-card>
            </b-col>
        </div>
        <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">

            <b-form-group
                id="name-group"
                label="Name"
                label-for="name"
                :invalid-feedback="this.errors && this.errors.name ? this.errors.name.join() : ''"
                :state="this.errors && this.errors.custom_url ? false : true"
            >
                <b-form-input id="name"
                              v-model="data.name"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                id="type-group"
                label="Type"
                label-for="type"
                :invalid-feedback="this.errors && this.errors.type ? this.errors.type.join() : ''"
                :state="this.errors && this.errors.type ? false : true"
            >
                <type id="type" v-model="data.type"></type>
            </b-form-group>

            <b-form-group
                v-if="data.type=='link'"
                id="custom_url-group"
                label=""
                label-for="custom_url"
                :invalid-feedback="this.errors && this.errors.custom_url ? this.errors.custom_url.join() : ''"
                :state="this.errors && this.errors.custom_url ? false : true"
            >
                <b-form-checkbox id="custom_url" switch v-model="data.custom_url">Custom URL</b-form-checkbox>
            </b-form-group>

            <b-form-group
                v-if="data.type == 'link' && data.custom_url"
                id="type-group"
                label="URL"
                label-for="url"
                :invalid-feedback="this.errors && this.errors.url ? this.errors.url.join() : ''"
                :state="this.errors && this.errors.url ? false : true"
            >
                <b-form-input id="url"
                              v-model="data.url"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                v-else-if="data.type == 'halaman'"
                id="hal_id-group"
                label="Halaman"
                label-for="hal_id"
                :invalid-feedback="this.errors && this.errors.hal_id ? this.errors.hal_id.join() : ''"
                :state="this.errors && this.errors.hal_id ? false : true"
            >
                <halaman id="hal_id"
                         v-model="data.hal_id"
                ></halaman>
            </b-form-group>


            <template v-slot:modal-footer>
                <div class="w-100">
                    <div class="d-flex">
                        <b-button
                            variant="secondary"
                            class="ml-auto"
                            @click="hideModalCommit"
                        >
                            Tutup
                        </b-button>

                        <b-button
                            v-if="action == 'store'"
                            variant="primary"
                            class="ml-1"
                            @click="store"
                        >
                            Simpan
                        </b-button>

                        <b-button
                            v-else
                            variant="primary"
                            class="ml-1"
                            @click="update"
                        >
                            Update
                        </b-button>
                    </div>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>
    import Nested from "./Nested.vue";
    import Type from "./SelectMenuType.vue";
    import Halaman from "./SelectHalaman.vue";

    export default {
        name: "nested-with-vmodel",
        display: "Nested (v-model & vuex)",
        order: 16,
        components: {
            Nested, Type, Halaman
        },
        created() {
            this.getData();
            this.$store.commit("setDataModal", this.data2);
        },
        computed: {
            user() {
                return this.$store.state.auth.data;
            },
            data: {
                get: function () {
                    return this.$store.state.dataModal;
                },
                set: function (newValue) {
                    return this.$store.commit("setDataModal", newValue);
                }
            },
            action: {
                get: function () {
                    return this.$store.state.action;
                },
                set: function (newValue) {
                    return this.$store.commit("setAction", newValue);
                }
            },
            showModal: {
                get: function () {
                    return this.$store.state.showModal;
                },
                set: function (newValue) {
                    return this.$store.commit("setShowModal", newValue);
                }
            },
            refreshState(){
                return this.$store.state.refresh;
            }
        },
        watch:{
            refreshState(){
              this.refresh()
          }
        },

        data() {
            return {
                children: [],
                modal_title: "Tambah Menu",
                data2: {
                    "id": "",
                    "parent_id": "",
                    "type": "",
                    "url": "",
                    "name": "",
                    "custom_url": 0,
                    "hal_id": "",
                },
                errors: null,
            }
        },
        methods: {
            refresh() {
                this.getData();
            },
            create() {
                this.showModalCommit()
                this.$store.commit("setAction", "store");
            },
            store() {
                this.axios.post('/api/menu', this.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: 'success'
                        });
                        this.refresh();
                    }
                });
            },
            update() {
                this.axios.put(`/api/menu/${this.data.id}`, this.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: 'success'
                        });
                        this.refresh();
                    }
                });
            },
            getData() {
                this.axios.get('/api/menu').then(res => {
                    this.children = res.data;
                });
            },
            hideModalCommit() {
                this.$store.commit("setShowModal", false);
            },
            showModalCommit() {
                this.$store.commit("setShowModal", true);
            },
        }
    };
</script>
