<template>
    <AppHeader fixed class="bg-header text-white shadow">
        <SidebarToggler class="d-lg-none" display="md" mobile/>
        <b-link class="navbar-brand" to="#">
            <img class="navbar-brand-full" src="/img/logo.png" width="89" height="35" alt="Logo">
            <img class="navbar-brand-minimized" src="/img/brand/sygnet.svg" width="30" height="30" alt="Logo">
        </b-link>
        <SidebarToggler class="d-md-down-none" display="lg" :defaultOpen=true>
        </SidebarToggler>
        <!-- <b-navbar-nav class="d-md-down-none">
           <b-nav-item class="px-3" to="/dashboard">Dashboard</b-nav-item>
           <b-nav-item class="px-3" to="/users" exact>Users</b-nav-item>
           <b-nav-item class="px-3">Settings</b-nav-item>
         </b-navbar-nav>-->
        <b-navbar-nav class="ml-auto">
            <Notif></Notif>
            <DefaultHeaderDropdownAccnt/>
        </b-navbar-nav>
        <!--<AsideToggler class="d-none d-lg-block" />-->
        <!--<AsideToggler class="d-lg-none" mobile />-->
    </AppHeader>
</template>
<script>
    import {Header as AppHeader, SidebarToggler, AsideToggler} from '@coreui/vue'
    import DefaultHeaderDropdownAccnt from './DefaultHeaderDropdownAccnt'
    import Notif from './Notif'

    export default {
        name: 'DefaultHeader',
        components: {
            AsideToggler,
            AppHeader,
            DefaultHeaderDropdownAccnt,
            SidebarToggler,
            Notif
        }
    }
</script>
