<template>
    <div>
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item>Chat</el-breadcrumb-item>
            <el-breadcrumb-item class="text-capitalize">{{user.name}}</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="wrapper border-0">
            <div class="animated fadeIn border-0">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header" v-if="survey.data">{{ survey.data.survey_nama_kegiatan }}</div>
                            <div class="card-body height3">
                                <ul class="chat-list">
                                    <li v-if="v.user" :class="v.user.id == user.id ? 'out' : 'in'" v-for="(v,i) in chats" :key="i">
                                        <div class="chat-img">
                                            <img :alt="v.user.name" :src="v.user.avatar">
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-message">
                                                <h5>{{ v.user.name }}</h5>
                                                <p>{{v.chat}}</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="p-3">
                                <b-input-group>
                                    <b-form-input type="text" v-model="chat"></b-form-input>

                                    <b-input-group-append>
                                        <b-button variant="primary" @click="send">Kirim</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <list-file ref="lf" :survey="survey" variant="excel" v-if="survey"> </list-file>
                        <list-file ref="lf2" :survey="survey" variant="pdf" v-if="survey"> </list-file>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ListFile from "../survey/ListFile";
    export default {
        components:{
            ListFile
        },
        data() {
            return {
                "survey": '',
                "chat": null,
                "chats": [],
                "links": "",
                "meta": ""
            }
        },
        created() {
            this.getData();
        },
        mounted(){
           this.listenChannel();
        },
        computed: {
            id() {
                return this.$route.params.survey_id
            },
            user() {
                return this.$store.state.auth.data;
            }
        },
        methods: {
            getData() {
                var id = this.id;
                this.axios.get(`/api/survey/${id}`).then(res => {
                    this.survey = res.data;
                });
                this.axios.get(`/api/chat/${id}`).then(res => {
                    this.meta = res.data.meta;
                    this.links = res.data.links;
                    res.data.data.forEach(v=>{
                        this.chats.unshift(v);
                    });
                });
            },
            send() {
                this.axios.post('/api/chat', {
                    'id': this.id,
                    'chat': this.chat,
                    'user_id': this.user.id
                }).then(res => {
                    this.chat = null;
                    // if (res.data.status) {
                    //     this.chats.push(res.data.data);
                    // }
                }).catch(() => {
                    this.$message.error({
                        message: 'error',
                    });
                });
            },
            listenChannel() {
                var survey_id = this.id;
                Echo.channel(`chat.${survey_id}`)
                    .listen('.chat.send', (e) => {
                       var id = e.chat_id;
                       this.axios.get(`/api/chat/${id}/edit`).then(res=>{
                          this.chats.push(res.data);
                       });
                    });
            },
        },
    }
</script>

<style scoped>
    .chat-list {
        padding: 0;
        font-size: .8rem;
    }

    .chat-list li {
        margin-bottom: 10px;
        overflow: auto;
        color: #ffffff;
    }

    .chat-list .chat-img {
        float: left;
        width: 48px;
    }

    .chat-list .chat-img img {
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 50px;
        width: 100%;
    }

    .chat-list .chat-message {
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 50px;
        background: #5a99ee;
        display: inline-block;
        padding: 10px 20px;
        position: relative;
    }

    .chat-list .chat-message:before {
        content: "";
        position: absolute;
        top: 15px;
        width: 0;
        height: 0;
    }

    .chat-list .chat-message h5 {
        margin: 0 0 5px 0;
        font-weight: 600;
        line-height: 100%;
        font-size: .9rem;
    }

    .chat-list .chat-message p {
        line-height: 18px;
        margin: 0;
        padding: 0;
    }

    .card-body{
        max-height: 65vh;
        overflow: auto;
    }

    .chat-list .chat-body {
        margin-left: 20px;
        float: left;
        width: 70%;
    }

    .chat-list .in .chat-message:before {
        left: -12px;
        border-bottom: 20px solid transparent;
        border-right: 20px solid #5a99ee;
    }

    .chat-list .out .chat-img {
        float: right;
    }

    .chat-list .out .chat-body {
        float: right;
        margin-right: 20px;
        text-align: right;
    }

    .chat-list .out .chat-message {
        background: #fc6d4c;
    }

    .chat-list .out .chat-message:before {
        right: -12px;
        border-bottom: 20px solid transparent;
        border-left: 20px solid #fc6d4c;
    }

    .card .card-header:first-child {
        -webkit-border-radius: 0.3rem 0.3rem 0 0;
        -moz-border-radius: 0.3rem 0.3rem 0 0;
        border-radius: 0.3rem 0.3rem 0 0;
    }

    .card .card-header {
        background: #17202b;
        border: 0;
        font-size: 1rem;
        padding: .65rem 1rem;
        position: relative;
        font-weight: 600;
        color: #ffffff;
    }

    .content {
        margin-top: 40px;
    }
</style>
