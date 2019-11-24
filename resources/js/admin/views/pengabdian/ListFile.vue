<template>
   <div v-if="list.length > 0">
       <ul class="list-group pr-3 pl-md-4">
           <li class="list-group-item d-flex"
               v-for="(item, index) in list"
               :key="index">
               <span class="fa fa-file-pdf-o fa-4x text-danger" v-if="item.data.file_ext == 'pdf'"></span>
               <span class="fa fa-file-excel-o fa-4x text-success" v-else="item.data.file_ext == 'xlsx'"></span>
               <button class="ml-3 btn btn-link text-info"
                       @click="editFile(item)"
                       v-if="item.mod.is_edit == false">{{item.data.file_title}}
               </button>
               <div class="ml-3" v-else>
                   <b-form-group
                           id="file_name-group"
                           label="Edit Nama File"
                           label-for="file_name"
                   >
                       <b-form-input id="file_name"
                                     @change="updateFile"
                                     v-model="item.data.file_title"
                       ></b-form-input>
                   </b-form-group>
               </div>
               <div class="ml-auto">
                   <!-- <button class="btn btn-primary">
                        <span class="fa fa-edit"></span>
                    </button>-->
                   <a :href="item.links.download" target="_blank" class="btn btn-primary">
                       <span class="fa fa-download"></span>
                   </a>
                   <button class="btn btn-danger" @click="destroy(item)" v-if="user.role == 'user'">
                       <span class="fa fa-trash-o"></span>
                   </button>
               </div>
           </li>
       </ul>
   </div>
    <div v-else>
        <h4 class="text-center">Belum ada file diupload</h4>
    </div>
</template>

<script>
    export default {
        props: ['penelitian'],
        created() {
            this.getData();
        },
        computed: {
            refreshState() {
                return this.$store.state.refresh
            },
            user() {
                return this.$store.state.auth.data;
            }
        },
        data() {
            return {
                data: '',
                fileList: [],
                modal_title: "Edit Slider",
                errors: '',
                list: [],
            };
        },
        methods: {
            getData() {
                var id = this.penelitian.data.penelitian_id;
                if(id){
                    this.axios.get(`/api/file/${id}`).then(res => {
                        this.list = res.data;
                    });
                }
            },
            destroy(item) {
                this.$confirm('Apakah Kamu Yakin?', 'Warning', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    type: 'warning'
                }).then(() => {
                    this.axios.delete(item.links.destroy).then(res => {
                        this.$message({
                            message: res.data.message,
                            type: res.data.text,
                        });
                        this.getData();
                    })
                }).catch(() => {
                    this.$message({
                        message: 'Batal hapus',
                        type: 'info',
                    });
                });
            },
            editFile(item) {
                this.data = item;
                this.data.mod.is_edit = !this.data.mod.is_edit;
            },
            updateFile() {
                this.axios.put(this.data.links.update, this.data.data).then(res=>{
                    if(res.data.status){
                        this.$message({
                            message:res.data.message,
                            type:res.data.status,
                        });
                        this.data.mod.is_edit = !this.data.mod.is_edit;
                    }
                })
            },
        }
    }
</script>
