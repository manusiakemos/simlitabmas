<template>
    <AppHeaderDropdown right no-caret class="mr-3">
        <template slot="header">
            <img
                :src="user.avatar"
                class="img-avatar"
                alt="admin@bootstrapmaster.com"/>
        </template>
        <template slot="dropdown">
            <div class="m-0">
                <b-dropdown-header tag="div" class="text-center bg-primary"><strong>{{user.username}}</strong></b-dropdown-header>
                <!--<b-dropdown-item><i class="fa fa-bell-o"/> Updates
                    <b-badge variant="info">{{ itemsCount }}</b-badge>
                </b-dropdown-item>
                <b-dropdown-item><i class="fa fa-envelope-o"/> Messages
                    <b-badge variant="success">{{ itemsCount }}</b-badge>
                </b-dropdown-item>
                <b-dropdown-item><i class="fa fa-tasks"/> Tasks
                    <b-badge variant="danger">{{ itemsCount }}</b-badge>
                </b-dropdown-item>
                <b-dropdown-item><i class="fa fa-comments"/> Comments
                    <b-badge variant="warning">{{ itemsCount }}</b-badge>
                </b-dropdown-item>
                <b-dropdown-header
                    tag="div"
                    class="text-center">
                    <strong>Settings</strong>
                </b-dropdown-header>-->
                <b-dropdown-item @click="move('/profile')"><i class="fa fa-user"/> Profile</b-dropdown-item>
                <!--<b-dropdown-item @click="move('/setting')"><i class="fa fa-wrench"/> Settings</b-dropdown-item>-->
                <b-dropdown-divider/>
                <b-dropdown-item @click="logout"><i class="fa fa-lock"/> Logout</b-dropdown-item>
            </div>
        </template>
    </AppHeaderDropdown>
</template>

<script>
    import {HeaderDropdown as AppHeaderDropdown} from '@coreui/vue'

    export default {
        name: 'DefaultHeaderDropdownAccnt',
        components: {
            AppHeaderDropdown
        },
        data: () => {
            return {itemsCount: 42}
        },
        computed:{
            user(){
                return this.$store.state.auth.data;
            }
        },
        methods: {
            logout() {

                this.$confirm('Apakah Kamu Yakin?', 'Logout Sekarang', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    type: 'warning'
                }).then(() => {
                    this.$store.commit('setAuth', {
                        "status": false,
                        "text": "",
                        "message": "",
                        "data": {
                            "id": "",
                            "name": "",
                            "username": "",
                            "password": "",
                            "password_confirmation": "",
                            "avatar": "",
                            "api_token": "",
                            "role": "",
                            "links": {
                                "update": "",
                                "edit": "",
                                "avatar": ""
                            }
                        }
                    });
                    this.$router.push({path:'/pages/login'});
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Logout batal'
                    });
                });
            },
            move(url) {
                this.$router.push({
                    path:url
                })
            },
        },
    }
</script>
