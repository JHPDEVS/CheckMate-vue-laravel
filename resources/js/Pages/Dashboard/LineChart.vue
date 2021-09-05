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
            backgroundColor: "rgba(246, 153, 63, 0.1)",
            borderColor: "rgba(246, 153, 63, 0.8)",
            borderWidth: 2,  
            data: this.chartData
          }
        ]
      }, this.options)
  },
};
</script>
