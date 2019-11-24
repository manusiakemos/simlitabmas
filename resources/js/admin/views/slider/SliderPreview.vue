<template>
    <div>
        <el-carousel :interval="4000"
                     arrow="always"
                     height="400px"
                     class="text-center"
                     indicator-position="outside"
        >
            <el-carousel-item v-for="(item,index) in lists" :label="item.data.slide_nama" :key="index">
                <el-image
                    style="height: 400px"
                    :src="item.mod.url"
                    fit="scale-down">
                </el-image>
            </el-carousel-item>
        </el-carousel>
    </div>
</template>

<style>
    .el-carousel__item h3 {
        color: #475669;
        font-size: 18px;
        opacity: 0.75;
        line-height: 300px;
        margin: 0;
    }

    .el-carousel__item:nth-child(2n) {
        background-color: #99a9bf;
    }

    .el-carousel__item:nth-child(2n+1) {
        background-color: #d3dce6;
    }
</style>

<script>
    export default {
        data() {
            return {
                lists: ''
            }
        },
        created() {
            this.refreshData();
        },
        methods: {
            refreshData() {
                this.axios.get('/api/slider').then(res => {
                    this.lists = res.data;
                })
            },
        },
        computed:{
            refreshState(){
                return this.$store.state.refresh
            }
        },
        watch:{
            refreshState(){
                this.refreshData();
            }
        }
    }
</script>
