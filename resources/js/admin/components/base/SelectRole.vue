<template>
    <el-select v-model="propModel" placeholder="Pilih Role">
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
        props:['value'],
        data () {
            return {
                options: [
                    {
                        value:'super-admin',
                        text: 'Super Admin'
                    },
                    {
                        value:'admin',
                        text: 'Admin'
                    },
                    {
                        value:'user',
                        text: 'User'
                    }
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
        }
    }
</script>
