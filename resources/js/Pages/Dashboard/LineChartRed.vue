<script>
import { Line } from "vue3-chart-v2";

export default {
  extends: Line,
   props: {
    chartData: {
      type: Array,
      required: false
    },
    chartLabels: {
   type: Array,
   required: true
   },
    options: {
      type: Object,
      default: null
    },
  },
  mounted() { 
    this.renderChart({
    labels: this.chartLabels,
    datasets: [
          {
            label: '명',
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 2,  
            data: this.chartData
          }
        ]
      }, this.options)
  },
};
</script>
