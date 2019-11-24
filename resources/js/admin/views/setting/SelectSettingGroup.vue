<template>
    <el-select v-model="propModel" placeholder="Pilih Group">
        <el-option
            v-for="(item, index) in options"
            :key="index"
            :label="item.text"
            :value="item.value">
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
                    return this.value.toString();
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        },
        methods: {
            getData() {
                this.axios.get('/api/select-options/group').then(res=>{
                    this.options = res.data;
                })
            },
        },
    }
</script>
