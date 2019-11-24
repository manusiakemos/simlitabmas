@verbatim
    <div id="mySlider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#mySlider"
                v-for="(v, i) in list"
                :data-slide-to="i"
                :class="i == 0 ? 'active' : ''"></li>
        </ol>
        <div class="carousel-inner">
            <div v-for="(v, i) in list" :class="i == 0 ? 'carousel-item active' : 'carousel-item'">
                <img class="d-block w-100" :src="v.mod.url" :alt="v.data.slide_nama">
            </div>
        </div>
        <a class="carousel-control-prev" href="#mySlider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#mySlider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@endverbatim

@push('script')
    <script>
        new Vue({
            el: '#mySlider',
            data: {
                url: '{{ $url }}',
                list: []
            },
            created() {
                this.getData();
            },
            methods: {
                getData() {
                    console.log(this.url)
                    axios.get(this.url).then(res => {
                        this.list = res.data;
                    });
                }
            }
        })
    </script>
@endpush
