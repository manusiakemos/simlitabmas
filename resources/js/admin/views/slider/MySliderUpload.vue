<template>
    <div class="mt-3">
        <div>
            <b-tabs content-class="mt-3" fill>
                <b-tab title="Upload Slider" active>
                    <el-upload
                        drag
                        action="/api/slider"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :on-success="refreshData"
                        :file-list="fileList"
                        :headers="headers"
                        list-type="picture"
                        multiple>
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                        <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>
                    </el-upload>
                </b-tab>
                <b-tab title="Slider Preview">
                    <slider-preview></slider-preview>
                </b-tab>
            </b-tabs>
        </div>

        <!-- Modal Component -->
        <b-modal :title="modal_title" size="lg" v-model="showModal" @ok="showModal = false">
            <!--slider form-->
            <el-upload v-if="data"
                       class="w-100"
                       drag
                       :on-success="refreshData"
                       :action="data ? data.links.upload : '/'"
                       :headers="headers">
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>
            </el-upload>

            <div v-if="data" class="mt-3">
                <b-form-group
                    id="slide_nama-group"
                    label="Judul"
                    label-for="slide_nama"
                    :invalid-feedback="this.errors && this.errors.slide_nama ? this.errors.slide_nama.join() : ''"
                    :state="this.errors && this.errors.slide_nama ? false : true"
                >
                    <b-form-input id="slide_nama"
                                  v-model="data.data.slide_nama"
                    ></b-form-input>
                </b-form-group>
            </div>

            <!--footer-->
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
                        <b-button
                            class="ml-1"
                            variant="primary"
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
    import SliderPreview from "./SliderPreview";

    export default {
        components: {
            SliderPreview
        },
        created() {
            this.getData();
        },
        computed: {
            refreshState() {
                return this.$store.state.refresh
            }
        },
        watch: {
            showModal: function (value) {
                value == false ? this.errors = [] : '';
            }
        },
        data() {
            return {
                data: '',
                showModal: false,
                fileList: [],
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.auth.data.api_token
                },
                modal_title: "Edit Slider",
                errors: [],
            };
        },
        methods: {
            handleRemove(file, fileList) {
                this.axios.delete(file.remove).then(res => {
                    this.$message({
                        message: res.data.message,
                        type: res.data.text
                    });

                    this.commitRefresh();
                });
            },
            handlePreview(file) {
                this.axios.get(file.edit).then(res => {
                    this.data = res.data;
                });
                this.showModal = true;
            },
            getData() {
                this.axios.get('/api/slider').then(res => {
                    var d = res.data;
                    d.forEach(v => {
                        this.fileList.push({
                            "url": v.mod.url,
                            "name": v.data.slide_nama ? v.data.slide_nama : v.mod.name,
                            "remove": v.links.destroy,
                            "edit": v.links.edit,
                            "upload": v.links.upload,
                        });
                    })
                });
            },
            refreshData() {
                this.fileList = [];
                this.getData();
                this.commitRefresh();
            },
            update() {
                this.axios.put(this.data.links.update, this.data.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: res.data.text
                        });
                        this.showModal = false;
                        this.refreshData();
                    }
                }).catch(error => {
                    if (error.response) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
            commitRefresh() {
                this.$store.commit("setRefresh");
            },
        }
    }
</script>
