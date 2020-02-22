<template>
  <div class="wrapper">
    <el-breadcrumb separator="/" class="mb-3">
      <el-breadcrumb-item :to="{ path: '/dashboard' }">Dashboard</el-breadcrumb-item>
    </el-breadcrumb>
    <div class="animated fadeIn">
      <!--<widgets></widgets>-->
      <b-button variant="primary" v-on:click="print('canvas')" class="mb-3">
        Cetak
        <i class="fa fa-file-pdf-o"></i>
      </b-button>
      <div
        class="wrapper"
        id="canvas"
        style="height:100vh; width:100vw; max-width: 100%; min-height: 400px"
      >
        <b-card no-body class="p-3" min-height="300px">
          <h4>{{this.series[0].name}}</h4>
          <apexcharts
            v-if="show"
            width="100%"
            height="350"
            type="bar"
            :options="chartOptions"
            :series="series"
          ></apexcharts>

          <div class="mb-3"></div>

          <h4>{{this.series2[0].name}}</h4>
          <apexcharts
            v-if="show2"
            width="100%"
            height="350"
            type="bar"
            :options="chartOptions2"
            :series="series2"
          ></apexcharts>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
  name: "Chart",
  components: {
    apexcharts: VueApexCharts
  },
  created() {
    this.getData();
  },
  name: "dashboard",
  data() {
    return {
      show: false,
      show2: false,
      data: "",
      chartOptions: {
        chart: {
          id: "vuechart-example",
          toolbar: {
            show: false
          }
        },
        xaxis: {
          categories: []
        }
      },
      series: [
        {
          name: "",
          data: []
        }
      ],
      chartOptions2: {
        chart: {
          id: "vuechart-example",
          toolbar: {
            show: false
          }
        },
        xaxis: {
          categories: []
        }
      },
      series2: [
        {
          name: "",
          data: []
        }
      ],
      printObj: {
        id: "canvas",
        popTitle: "chart"
        // extraCss: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
      }
    };
  },
  methods: {
    print(selector) {
      // let canvas = document.getElementById("bar-chart-1");
      // this.canvas_base64 = canvas.toDataURL("image/png");
      this.$nextTick(() => {
        this.$htmlToPaper(selector);
      });
    },
    getData() {
      this.axios.post("/api/dashboard/chart/penelitian").then(res => {
        var d = res.data;
        d.data.forEach(v => {
          var c = this.chartOptions.xaxis.categories;
          var s = this.series[0];
          c.push(v.label);
          s.data.push(v.value);
        });
        this.series[0].name = d.title;
        this.show = true;
      });

      this.axios.post("/api/dashboard/chart/pengabdian").then(res => {
        var d = res.data;
        d.data.forEach(v => {
          var c = this.chartOptions2.xaxis.categories;
          var s = this.series2[0];
          c.push(v.label);
          s.data.push(v.value);
        });
        this.series2[0].name = d.title;
        this.show2 = true;
      });
    }
  }
};
</script>
