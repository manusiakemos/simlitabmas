<section class="section section-lg bg-gradient-default" id="my-footer">
    @verbatim
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div v-html="youtube.setting_value"></div>
                </div>

                <div class="col-lg-12 text-center">
                    <h2 class="display-3 text-white">{{alamat.setting_name}}</h2>
                    <div class="lead text-white" v-html="alamat.setting_value"></div>

                    <div class="d-lg-flex justify-content-center mt-5">
                        <div v-for="v in list" :key="v.setting_id" class="p-3 text-center">
                            <div class="icon icon-lg icon-shape bg-gradient-white shadow rounded-circle text-primary">
                                <i :class="v.setting_icon"></i>
                            </div>
                            <h5 class="text-white text-capitalize mt-3">{{v.setting_name}}</h5>
                            <p class="text-white mt-3">{{v.setting_value}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endverbatim
</section>

<footer class="section bg-gradient-orange text-default">
    <div class="container d-none d-sm-block">
        <div class="row align-items-center justify-content-md-between">
            <div class="col-md-6">
                <div class="copyright text-default">
                    <span class="fa fa-copyright"></span> {{date('Y')}}
                    <a href="/" target="_blank">{{config('app.name')}}</a>
                </div>
            </div>
            <div class="col-md-6" id="link-terkait">
                @verbatim
                    <ul class="nav nav-footer justify-content-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-default">
                                <strong>Link terkait</strong> <span class="fa fa-angle-right"></span>
                            </a>
                        </li>
                        <li class="nav-item" v-for="(v,i) in list" :key="i">
                            <a :href="v.setting_value" class="nav-link text-default" target="_blank">
                                {{v.setting_name}}
                            </a>
                        </li>
                    </ul>
                @endverbatim
            </div>
        </div>
    </div>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="copyright d-block d-sm-none">
            <span class="fa fa-copyright"></span> {{date('Y')}} <a href="https://www.creative-tim.com" target="_blank">{{config('app.name')}}</a>
        </div>
    </div>
</footer>

@push('script')
    <script>
        new Vue({
            el: '#my-footer',
            data: {
                list: [],
                alamat: '',
                youtube: '',
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    axios.get(`/web-api/setting/info`).then(res => {
                        this.list = res.data;
                    });
                    axios.get(`/web-api/setting-name/alamat`).then(res => {
                        this.alamat = res.data;
                    });
                    axios.get(`/web-api/setting-name/youtube`).then(res => {
                        this.youtube = res.data;
                    });
                }
            }
        })

        new Vue({
            el: '#link-terkait',
            data: {
                list: [],
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    axios.get(`/web-api/setting/link`).then(res => {
                        this.list = res.data;
                    });
                }
            }
        })
    </script>
@endpush
