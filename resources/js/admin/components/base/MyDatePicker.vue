<template>
    <el-date-picker
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            v-model="propModel"
            type="date"
            placeholder="Pilih Tanggal">
    </el-date-picker></template>
<script>
    export default {
        props:['value'],
        data () {
            return {
               config:{

               }
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
        }
    }
</script>
