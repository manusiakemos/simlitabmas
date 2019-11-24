<template>
    <div class="mt-3">
        <div class="row">
            <div :class="user.role == 'user' ? 'col-md-4' : 'col-md-12'"  v-if="user.role == 'user'">
                <el-upload
                    drag
                    :multiple="false"
                    :action="action"
                    :on-success="success"
                    :file-list="fileList"
                    :show-file-list="false"
                    :on-error="errorManage"
                    :headers="headers"
                    list-type="picture">
                    <i class="el-icon-upload text-primary"></i>
                    <div class="el-upload__text">Tarik file atau <em>klik untuk upload</em></div>
                    <!--<div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>-->
                </el-upload>
                <small class="text-danger" v-if="errors">{{errors.errors.file.join()}}</small>
            </div>
            <div :class="user.role == 'user' ? 'col-md-8' : 'col-md-12'">
                <list-file ref="lf" :penelitian="penelitian"></list-file>
            </div>
        </div>
    </div>
</template>
<script>
    import ListFile from "./ListFile";

    export default {
        props: ['title', 'action', 'penelitian', 'variant'],
        components: {
            ListFile
        },
        computed: {
            refreshState() {
                return this.$store.state.refresh
            },
            user(){
                return this.$store.state.auth.data
            }
        },
        data() {
            return {
                data: '',
                showModal: false,
                fileList: [],
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.auth.data.api_token,
                    'Accept': 'application/json'
                },
                modal_title: "Edit Slider",
                errors: '',
                list: null,
            };
        },
        methods: {
            success(res) {
                this.$message({
                    message: res.message, type: res.text
                })
                this.$refs.lf.getData();
            },
            errorManage(err) {
                this.errors = JSON.parse(err.message);
                console.log(this.errors);
            },
        }
    }
</script>
