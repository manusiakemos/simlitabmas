<template>
    <AppHeaderDropdown right no-caret class="mr-3">
        <template slot="header">
            <div>
                <i class="fa fa-bell text-primary" style="font-size: 1.2rem"></i>
                <b-badge pill variant="danger" v-if="unreadNotifications.length > 0">{{unreadNotifications.length}}</b-badge>
            </div>
        </template>
        <template slot="dropdown">
            <div class="m-0" v-if="unreadNotifications.length > 0">
                <b-dropdown-header tag="div" class="text-center bg-primary"><strong>Pemberitahuan</strong></b-dropdown-header>
                <b-dropdown-item v-for="(v, i) in unreadNotifications" :key="i" @click="routerPush(v)">
                    <i :class="v.data.type=='pengabdian' ? 'fa fa-bell text-info' : 'fa fa-bell text-success'"/> {{v.data.message}}
                    <!--<b-badge variant="info">{{ itemsCount }}</b-badge>-->
                </b-dropdown-item>
                <b-dropdown-item @click="readAll">Baca Semua</b-dropdown-item>
            </div>
            <div class="m-0" v-else>
                <b-dropdown-header tag="div" class="text-center"><strong>Tidak Ada Pemberitahuan</strong></b-dropdown-header>
            </div>
        </template>
    </AppHeaderDropdown>
</template>

<script>
    import {HeaderDropdown as AppHeaderDropdown} from '@coreui/vue'

    export default {
        name: 'Notif',
        components: {
            AppHeaderDropdown
        },
        created() {
            this.getData();
        },
        mounted() {
            var user = this.$store.state.auth.data;
            Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + user.api_token;
            if (user.api_token) {
                Echo.private(`App.User.${user.id}`)
                    .notification((notification) => {
                        this.$store.commit("setRefresh");
                        var obj = {
                            "id": notification.id,
                            "data": {"message": notification.message},
                        };
                        this.unreadNotifications.push(obj);
                    });
            }
        },
        data: () => {
            return {
                itemsCount: 42,
                unreadNotifications: []
            }
        },
        methods: {
            routerPush(v) {
                this.readMe(v);
                var current = this.$router.currentRoute;
                if(v.data.type == "pengabdian"){
                    var url = '/pengabdian';
                    if (current.path != '/pengabdian') {
                        this.$router.push({path: url});
                    }
                }else{
                    var url = '/penelitian';
                    if (current.path != '/penelitian') {
                        this.$router.push({path: url});
                    }
                }
            },
            readAll() {
                this.axios.post('/api/pemberitahuan/readall').then(res=>{
                    this.getData();
                });
            },
            readMe(v) {
                this.axios.post('/api/pemberitahuan/readme',{
                    id : v.id
                }).then(res=>{
                    this.getData();
                });
            },
            getData() {
                this.axios.get('/api/pemberitahuan/unread').then(res => {
                    this.unreadNotifications = res.data;
                });
            },
        },
    }
</script>
