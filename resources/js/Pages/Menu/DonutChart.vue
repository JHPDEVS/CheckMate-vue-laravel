<script>
import { Pie } from "vue3-chart-v2";

export default {
  extends: Pie,
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
    labels: ["출석","지각","결석"],
    datasets: [
          {
            backgroundColor: ["#42B2B8", "#FBBF24", "#F87171"],  
            data: this.chartData
          }
        ]
      }, this.options)
  },
};
</script>
