<template>
    <el-date-picker
            format="yyyy"
            value-format="yyyy"
            v-model="propModel"
            type="year"
            placeholder="Pilih Tahun">
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
                    return this.value.toString();
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        }
    }
</script>
