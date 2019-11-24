import Vue from "vue"
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist'
const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})
Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        config: {
            app_name: process.env.MIX_APP_NAME,
            creator:process.env.MIX_CREATOR_NAME,
            company:process.env.MIX_COMPANY_NAME,
        },
        auth: {
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
        },
        refresh:1,
        dataModal:'',
        showModal:false,
        action:'',
        uploadAction:'/api/berita/upload',
    },
    mutations: {
        setUploadAction (state, value) {
          state.uploadAction = value;
        },
        setAuth (state, value) {
            state.auth = value;
        },
        setDataModal (state, value) {
            state.dataModal = value;
        },
        setAction (state, value) {
            state.action = value;
        },
        setShowModal (state, value) {
            state.showModal = value;
        },
        setAuthData (state, value) {
            state.auth.data = value;
        },
        setRefresh (state) {
            state.refresh++;
        },
        resetRefresh(state){
            state.refresh = 1;
        }
    },
    plugins: [vuexLocal.plugin]
});

export default store;
