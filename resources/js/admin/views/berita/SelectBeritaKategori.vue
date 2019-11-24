<template>
    <el-select v-model="propModel" placeholder="Pilih Kategori">
        <el-option
            v-for="(item, index) in options"
            :key="index"
            :label="item.bk_nama"
            :value="item.bk_id">
        </el-option>
    </el-select>
</template>

<script>
    export default {
        created(){
            this.getData();
        },
        props:['value'],
        data () {
            return {
                options: [

                ],
            };
        },
        computed: {
            propModel: {
                get() {
                    return this.value;
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        },
        methods: {
            getData() {
                this.axios.get('/api/select-options/beritakategori').then(res=>{
                    this.options = res.data;
                })
            },
        },
    }
</script>
