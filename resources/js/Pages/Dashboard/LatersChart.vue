<template>
                    <div class="mx-auto">
                    <div class="card-body"> 
                    <div class="stat-title">최근 일주일 지각횟수</div>
                    <div class="stat-value">{{ sum }}</div>
                        <line-chart v-if="loaded" :height="200" :chart-data="count" :chart-labels="date" :options="options"  class="min-w-full"/>
                    </div>
                    </div>
</template>

<script>
    // import the styles 
import LineChart from './LineChart.vue'
    export default {
        data: () => ({
            loaded: false,
            ranking: [],
            arr: [],
            date: '',
            count: '',
            chartdata: null,
            sum: 0,
            options: {
                 maintainAspectRatio:false,
                responsive: true,
                   legend: {
        display: false,
    },
                scales: {
                    xAxes: [{
                        gridLines: false,
                        scaleLabel: false,
                          ticks: {
                            beginAtZero: true,
                            fontSize: 13,
                            stepSize: 1,
                            padding: 10,
                        }
                    }],
                    yAxes: [{
                        gridLines: false,
                        scaleLabel: false,
                        ticks: {
                            beginAtZero: true,
                            fontSize: 17,
                            stepSize: 1,
                            padding: 10,
                        }
                    }]
                }
            }
        }),
        components: {
            LineChart
        },
   
        mounted() {
            axios.get("/api/users/rank?attend=지각&teamId=2")
                .then(response => {
                    this.ranking = response.data.data
                    console.log(this.ranking);
                    this.date = response.data.data.map(element => element.date)
                    this.count = response.data.data.map(element => element.count)
                    this.sum = this.count.reduce(function add(sum, currValue) {
                        return sum + currValue;
                    }, 0);
                    this.loaded = true
                    // this.options.scales.yAxes.ticks.max = 5;
                })


        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
        },
    }
</script>
