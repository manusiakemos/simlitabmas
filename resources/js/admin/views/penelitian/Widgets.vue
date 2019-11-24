<template>
    <div class="animated fadeIn">
        <b-row>
            <b-col sm="6" md="3" v-for="(v,i) in list" :key="i">
                <b-card class="text-white bg-primary">
                    <div class="h4 mb-0">{{v.value}} Penelitian</div>
                    <small class="text-muted text-uppercase font-weight-bold">{{v.label}}</small>
                    <b-progress class="progress-white progress-xs mt-3" :value="v.percent"/>
                </b-card>
            </b-col>
        </b-row><!--/.row-->
    </div>
</template>

<script>
    export default {
        name: 'widgets',
        data () {
            return {
                msg: 'Widgets',
                list:''
            }
        },
        created(){
            this.getData();
        },

        methods: {
            getData() {
                this.axios.post('/api/dashboard/widget').then(res=>{
                    this.list = res.data;
                });
            },
        },
    }
</script>
