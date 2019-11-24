<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Profile</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">{{user.name}}</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <b-row>
                    <b-col cols="12">
                        <div class="mt-3">

                        </div>
                    </b-col>
                </b-row>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed:{
            user(){
                return this.$store.state.auth.data;
            }
        }
    }
</script>
