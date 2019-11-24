<template>
    <div class="wrapper">
        <el-breadcrumb separator="/" class="mb-3">
            <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="animated fadeIn">
            <!--<widgets></widgets>-->
            <b-button variant="primary"
                      v-on:click="print('printMe')"
                      class="mb-3">Cetak <i class="fa fa-file-pdf-o"></i></b-button>
            <div class="wrapper" style="height:100vh; width:100vw; max-width: 100%; min-height: 400px">
                <b-card no-body v-if="this.data.length > 0">
                    <line-chart :options="config.options" :chart-data="config.data" class="p-5"></line-chart>
                </b-card>
            </div>
        </div>
        <div id="printMe">
            <img v-if="canvas_base64" :src="canvas_base64" class="d-none d-print-block text-center">
        </div>
    </div>
</template>

<script>
    import LineChart from './LineChart'
    // import Widgets from './Widgets'

    export default {
        components: {
            LineChart,
            // Widgets
        },
        created() {
            this.getData();
        },
        name: 'dashboard',
        data() {
            return {
                data: [],
                canvas_base64: "",
                config: {
                    type: "line",
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: "Diagram Penelitian",
                                backgroundColor: [
                                   "#009688","#005a51","#7daf42"
                                ],
                                borderColor: 'salmon',
                                fill: false,
                                data: [],
                            },
                        ]
                    },
                    options: {
                        "responsive": true,
                        "maintainAspectRatio": false,
                        "title": {"display": true, "text": ""},
                        "legend": {"display": false},
                        "tooltips": {"mode": "index", "intersect": false},
                        "hover": {"mode": "nearest", "intersect": true},
                        "scales": {
                            "xAxes": [
                                {
                                    "display": true,
                                    "scaleLabel": {"display": true, "labelString": "Tahun"}
                                }
                            ],
                            "yAxes": [
                                {
                                    "display": true,
                                    "ticks": {
                                        "beginAtZero": true
                                    },
                                    "scaleLabel": {
                                        "display": true,
                                        "labelString": "Jumlah"
                                    }
                                },
                            ]
                        }
                    }
                },
                printObj: {
                    id: "printMe",
                    popTitle: 'good print',
                    extraCss: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
                }
            }
        },
        methods: {
            print(selector) {
                let canvas = document.getElementById("bar-chart");
                this.canvas_base64 = canvas.toDataURL("image/png");
                this.$nextTick(() => {
                    this.$htmlToPaper(selector);
                })
            },
            getData() {
                this.axios.post('/api/dashboard/chart').then(res => {
                    this.config.options.title.text = res.data.title;
                    res.data.data.forEach(v => {
                        this.config.data.labels.push(v.label);
                        this.data.push(v.value);
                        this.config.data.datasets[0].data.push(v.value);
                    });
                });
            },
        },
    }
</script>
