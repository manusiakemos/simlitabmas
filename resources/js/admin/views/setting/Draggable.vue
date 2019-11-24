<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <div class="row">
            <div class="col-12">
                <el-button
                    v-if="user.role == 'super-admin'"
                    @click="create"
                    type="primary" class="mb-3" icon="fa fa-plus" size="small"> Tambah </el-button>
                <el-button
                    @click="refresh"
                    type="primary" class="mb-3" icon="fa fa-refresh" size="small"> Refresh </el-button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-4" v-for="(data,index) in lists" :key="index">
                <b-card :header="index" bg-variant="primary" class="shadow" :data-id="index">
                    <draggable class="list-group p-0 m-0 text-dark" :list="data" group="people" @change="log">
                        <b-list-group-item
                            v-for="(element, index) in data"
                            :key="index"
                            :data-id="element.data.setting_id"
                            class="d-flex align-items-center">
                            {{ element.data.setting_name }}
                            <div class="ml-auto">
                                <b-button variant="primary" size="sm" @click="edit(element)">
                                    <span class="fa fa-edit"></span>
                                </b-button>
                                <b-button @click="destroy(element)" variant="danger" size="sm">
                                    <span class="fa fa-trash-o"></span>
                                </b-button>
                            </div>
                        </b-list-group-item>
                    </draggable>
                </b-card>
            </div>
        </div>
        <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">

            <b-form-group
                id="setting_name-group"
                label="Setting Name"
                label-for="setting_name"
                :invalid-feedback="this.errors && this.errors.setting_name ? this.errors.setting_name.join() : ''"
                :state="this.errors && this.errors.setting_name ? false : true"
            >
                <b-form-input
                    id="setting_name"
                    v-model="list.data.setting_name"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                id="setting_icon-group"
                label="Setting Icon"
                label-for="setting_icon"
                :invalid-feedback="this.errors && this.errors.setting_icon ? this.errors.setting_icon.join() : ''"
                :state="this.errors && this.errors.setting_icon ? false : true"
            >
                <b-form-input
                    id="setting_icon"
                    v-model="list.data.setting_icon"
                ></b-form-input>
            </b-form-group>

            <div class="mb-3">
                <a target="_blank" href="https://fontawesome.com/v4.7.0/cheatsheet/">Klik untuk melihat referensi icon</a>
            </div>

            <b-form-group
                id="setting_group-group"
                label="Setting Group"
                label-for="setting_group"
                :invalid-feedback="this.errors && this.errors.setting_group ? this.errors.setting_group.join() : ''"
                :state="this.errors && this.errors.setting_group ? false : true"
            >
                <setting-group v-model="list.data.setting_group"></setting-group>
            </b-form-group>

            <b-form-group
                id="setting_input-group"
                label="Setting Input Type"
                label-for="setting_input"
                :invalid-feedback="this.errors && this.errors.setting_value ? this.errors.setting_value.join() : ''"
                :state="this.errors && this.errors.setting_value ? false : true"
            >
                <setting-input v-model="list.data.setting_input"></setting-input>
            </b-form-group>

            <b-form-group
                v-if="list.data.setting_input"
                id="setting_value-group"
                label="Setting Value"
                label-for="setting_value"
                :invalid-feedback="this.errors && this.errors.setting_value ? this.errors.setting_value.join() : ''"
                :state="this.errors && this.errors.setting_value ? false : true"
            >
                <b-form-input
                    v-if="list.data.setting_input=='text'"
                    id="setting_value"
                    v-model="list.data.setting_value"
                ></b-form-input>
                <my-editor
                    v-else-if="list.data.setting_input=='editor'"
                    v-model="list.data.setting_value"
                ></my-editor>
                <my-editor
                    v-else-if="list.data.setting_input=='html'"
                    v-model="list.data.setting_value"
                ></my-editor>
            </b-form-group>

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
</template>
<script>
    import draggable from "vuedraggable";
    import MyEditor from "../../components/base/MyEditor";
    import SettingGroup from "./SelectSettingGroup";
    import SettingInput from "./SelectSettingInput";

    export default {
        order: 1,
        components: {
            draggable,
            MyEditor,
            SettingGroup,
            SettingInput
        },
        created() {
            this.getData();
            this.list2 = this.list;
        },
        computed: {
            user() {
                return this.$store.state.auth.data;
            }
        },
        data() {
            return {
                list: {
                    "data": {
                        "setting_id": "",
                        "setting_icon": "",
                        "setting_name": "",
                        "setting_group": "",
                        "is_public": "",
                        "setting_value": "",
                        "created_at": "",
                        "updated_at": "",
                        "setting_input": ""
                    },
                    "mod": "",
                    "links": {
                        "store": "/api/setting",
                        "edit": "",
                        "show": "",
                        "update": "",
                        "destroy": ""
                    }
                },
                list2: null,
                lists: null,
                showModal: false,
                action: "store",
                errors: null,
                modal_title: "Edit Setting",
            };
        },
        methods: {
            log: function (evt) {
                var add = evt.added;
                var str = JSON.stringify(add);
                var add = JSON.parse(str);
                if (add.element) {
                    var id = add.element.data.setting_id;
                    var el = $(`[data-id="${id}"]`).closest('.card');
                    var parent = el.data('id');

                    this.axios.put(`/api/setting/order/${id}`, {parent: parent}).then(res => {
                        this.$message({
                            message: res.data.message,
                            type: res.data.status
                        })
                    })
                }
            },
            getData() {
                this.axios.get('/api/setting').then(res => {
                    var lists = res.data;
                    this.lists = _.groupBy(lists, 'data.setting_group');
                })
            },
            edit(element) {
                this.modal_title = "Edit Setting";
                this.action = 'update';
                this.list = _.cloneDeep(element);
                this.showModal = true;
            },
            create() {
                this.modal_title = "Tambah Setting";
                this.list = _.cloneDeep(this.list2);
                this.action = 'store';
                this.showModal = true;
            },
            store() {
                this.axios.post(this.list.links.store, this.list.data).then(res => {
                    if (res.data.status) {
                        this.refresh();
                        this.showModal = false;
                        this.$message({
                            message: res.data.message,
                            type: res.data.text,
                        });
                    }
                }).catch(err => {
                    this.$message({
                        message: err.response.message,
                        type: 'danger',
                    });
                })
            },
            update() {
                this.axios.put(this.list.links.update, this.list.data).then(res => {
                    if (res.data.status) {
                        this.refresh();
                        this.showModal = false;
                        this.$message({
                            message: res.data.message,
                            type: res.data.text,
                        });
                    }
                }).catch(err => {
                    this.$message({
                        message: err.response.message,
                        type: 'danger',
                    });
                })
            },
            destroy(element) {
                this.list = element;
                this.$confirm('Apakah Kamu Yakin?', 'Warning', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    type: 'warning'
                }).then(()=>{
                    this.axios.delete(this.list.links.destroy, this.list.data).then(res => {
                        if (res.data.status) {
                            this.refresh();
                            this.$message({
                                message: res.data.message,
                                type: res.data.text,
                            });
                        }
                    }).catch(err => {
                        this.$message({
                            message: err.response.message,
                            type: 'danger',
                        });
                    })
                }).catch(()=>{
                    this.$message({
                        message: 'Batal hapus',
                        type: 'info',
                    });
                });

            },
            refresh() {
                this.getData();
            },
        }
    };
</script>
