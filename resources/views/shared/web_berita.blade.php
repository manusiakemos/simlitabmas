<div class="container" id="{{$id}}">
    <div class="row row-grid align-items-center ml-lg-5 mr-lg-5">
        @verbatim
            <div class="col-12" v-if="list.length > 0">
                <h1 class="text-center text-white" v-if="variant == 'dark'">{{title}}</h1>
                <h1 class="text-center text-dark" v-else>{{title}}</h1>
            </div>
            <div class="card-columns">
                <div v-for="(v,i) in list" :key="i" class="card bg-default shadow border-0" v-if="v">
                    <img :src="v.mod.image_src" class="card-img-top">
                    <blockquote class="card-blockquote">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95"
                             class="svg-bg">
                            <polygon points="0,52 583,95 0,95" class="fill-default"></polygon>
                            <polygon points="0,42 583,95 683,0 0,95" opacity=".2" class="fill-default"></polygon>
                        </svg>
                        <a :href="v.mod.url_berita">
                            <h6 class="display-5 font-weight-bold text-white">{{ v.data.berita_judul }}</h6>
                        </a>
                        <small class="text-secondary">{{v.mod.created_at}}</small>
                    </blockquote>
                    <div class="badge badge-success text-white badge-kategori">{{ v.data.kategori.bk_nama }}</div>
                </div>
            </div>
        @endverbatim
    </div>
</div>

@push('script')
    <script>
        var id = `{{$id}}`;
        var url = `{{$url}}`;
        var title = `{{$title}}`;
        var variant = `{{isset($variant) ? $variant : 'light'}}`;
        new Vue({
            el: `#${id}`,
            data: {
                list: [],
                title: title,
                variant: variant,
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    axios.get(url).then(res => {
                        this.list = res.data;
                    });
                }
            }
        })
    </script>
@endpush
