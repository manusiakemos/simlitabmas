<header class="header-global">
    <nav id="navbar-main"
         class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light headroom text-white bg-default">
        <div class="container">
            <a class="navbar-brand mr-lg-5" href="/">
                <img alt="image" src="{{ asset('img/brand/white.png') }}">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global"
                    aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbar_global">
                <div class="navbar-collapse-header">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="/">
                                <img alt="image" src="{{ asset('img/brand/blue.png') }}">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                    data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
            @verbatim
                <!-- menu -->
                    <div id="my-nav-menu">
                        <menu-list :list="list"></menu-list>
                    </div>
                    <!-- info -->
                    <ul class="navbar-nav align-items-lg-center ml-lg-auto" id="my-setting-info">
                        <li class="nav-item d-none d-lg-block ml-lg-4">
                            <a href="/admin" target="_blank"
                               class="btn btn-neutral btn-icon">
                            <span class="btn-inner--icon">
                              <i class="fa fa-sign-in"></i>
                            </span>
                                <span class="nav-link-inner--text">LOGIN</span>
                            </a>
                        </li>
                    </ul>

                @endverbatim
            </div>
        </div>
    </nav>
</header>

@push('script')
   {{-- <script>
        new Vue({
            el: '#my-setting-info',
            data: {
                list: []
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    axios.get(`/web-api/setting/info`).then(res => {
                        this.list = res.data;
                    });
                }
            }
        })
    </script>--}}

    <script>
        Vue.component('menu-list', {
            props: ['list'],
            name: 'menu-list',
            template: `
                <ul class="navbar-nav navbar-nav-hover align-items-lg-center text-uppercase">
                    <li class="nav-item dropdown" v-for="(v, i) in  list" :key="v.id">
                        <a href="#" class="nav-link" data-toggle="dropdown" role="button" v-if="v.children.length > 0">
                            <i class="fa fa-angle-down d-lg-none"></i>
                            <span class="nav-link-inner--text text-warning">@{{ v.data.name }}</span>
                        </a>
                        <a :href="v.mod.url_to" :class="v.data.url == 'admin' ? 'nav-link d-block d-sm-none' : 'nav-link'" v-else>
                            <span class="nav-link-inner--text text-warning">@{{ v.data.name }}</span>
                        </a>
                        <div class="dropdown-menu" v-if="v.children.length > 0">
                            <div v-for="(item, index) in v.children">
                                <a v-if="item.children.length < 1"
                                   :href="item.mod.url_to" class="dropdown-item">@{{ item.data.name }}</a>
                                <menu-list :list="item.children" v-if="item.children.length > 0"></menu-list>
                            </div>

                        </div>
                    </li>
                 </ul>
            `
        })

        new Vue({
            el: '#my-nav-menu',
            data: {
                list: []
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    axios.get(`/web-api/menu`).then(res => {
                        this.list = res.data;
                    });
                }
            }
        })
    </script>
@endpush
