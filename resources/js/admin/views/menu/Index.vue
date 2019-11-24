x<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Profile</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">{{user.name}}</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <draggable></draggable>
            </div>
        </div>
    </div>
</template>

<script>
    import Draggable from "./Draggable"
    export default {
        components: {Draggable},
        computed:{
            user(){
                return this.$store.state.auth.data;
            }
        }
    }
</script>
