<template>
    <div class="app">
        <DefaultHeader/>
        <div class="app-body">
            <AppSidebar fixed>
                <SidebarHeader/>
                <SidebarForm/>
                <SidebarNav v-if="nav" :navItems="nav"></SidebarNav>
                <SidebarFooter/>
                <SidebarMinimizer/>
            </AppSidebar>
            <main class="main">
                <!--<Breadcrumb :list="list"/>-->
                <div class="container-fluid">
                    <router-view class="mt-3"></router-view>
                </div>
            </main>
           <!-- <AppAside fixed>
                <DefaultAside/>
            </AppAside>-->
        </div>
        <DefaultFooter/>
    </div>
</template>

<script>
    import {
        Sidebar as AppSidebar,
        SidebarFooter,
        SidebarForm,
        SidebarHeader,
        SidebarMinimizer,
        SidebarNav,
        Aside as AppAside,
        // Breadcrumb
    } from '@coreui/vue'
    // import DefaultAside from './DefaultAside'
    import DefaultHeaderDropdownAccnt from './DefaultHeaderDropdownAccnt'
    import DefaultHeader from './DefaultHeader'
    import DefaultFooter from './DefaultFooter'

    export default {
        name: 'DefaultContainer',
        components: {
            AppSidebar,
            AppAside,
            // Breadcrumb,
            // DefaultAside,
            DefaultHeaderDropdownAccnt,
            SidebarForm,
            SidebarFooter,
            SidebarHeader,
            SidebarNav,
            SidebarMinimizer,
            DefaultFooter,
            DefaultHeader
        },
        created() {
            var role = this.$store.state.auth.data.role;
            // super admin
            if (role == 'super-admin') {
                var items = [
                    {
                        title: true,
                        name: this.$store.state.auth.data.username,
                        class: '',
                        wrapper: {
                            element: '',
                            attributes: {}
                        }
                    },
                    {
                        name: 'Dashboard',
                        url: '/dashboard',
                        icon: 'fa fa-dashboard',
                    },
                    {
                        name: 'User',
                        url: '/user',
                        icon: 'fa fa-user',
                    },
                ]
            }
// admin
            else if (role == 'admin') {
                var name = 'Kepala Kantor'
                var items = [
                    {
                        title: true,
                        name: name,
                        class: 'text-white',
                        wrapper: {
                            element: '',
                            attributes: {}
                        }
                    },
                    {
                        name: 'Dashboard',
                        url: '/dashboard',
                        icon: 'fa fa-dashboard',
                    },
                    {
                        name: 'Penelitian',
                        url: '/penelitian',
                        icon: 'fa fa-flask',
                    },
                    {
                        name: 'Pengabdian',
                        url: '/pengabdian',
                        icon: 'fa fa-certificate',
                    },
                ];
            }else{
                var name = 'Admin'
                var items = [
                    {
                        title: true,
                        name: name,
                        class: '',
                        wrapper: {
                            element: '',
                            attributes: {}
                        }
                    },
                    {
                        name: 'Dashboard',
                        url: '/dashboard',
                        icon: 'fa fa-dashboard',
                    },
                    {
                        name: 'Penelitian',
                        url: '/penelitian',
                        icon: 'fa fa-flask',
                    },
                    {
                        name: 'Pengabdian',
                        url: '/pengabdian',
                        icon: 'fa fa-certificate',
                    },
                    {
                        name: 'Pegawai',
                        url: '/anggota',
                        icon: 'fa fa-users',
                    },
                ];
            }
            items.push(
                {
                    title: true,
                    name: 'General',
                    class: '',
                    wrapper: {
                        element: '',
                        attributes: {}
                    }
                },
                {
                    name: 'Profile',
                    url: '/profile',
                    icon: 'fa fa-address-card-o',
                },
            )
           /*if(role != 'user'){
               items.push(
                   {
                       name: 'Setting',
                       url: '/setting',
                       icon: 'fa fa-gear',
                   },
               )
           }*/

            this.nav = items;
        },
        data() {
            return {
                nav: ''
            }
        },
        computed: {
            name() {
                return this.$route.name
            },
            list() {
                return this.$route.matched.filter((route) => route.name || route.meta.label)
            }
        },
    }
</script>
