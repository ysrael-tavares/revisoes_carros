<template>
    <Pie :data="data" :options="options" />
</template>

<script>
// DataPage.vue
import { Pie } from 'vue-chartjs'
import { Chart, ArcElement, Tooltip, Legend, Title} from 'chart.js'
import ChartDataLabels from 'chartjs-plugin-datalabels'

Chart.register(Title, Tooltip, Legend, ArcElement, ChartDataLabels)

export default {
    name: 'PieChart',
    components: { Pie },
    props: {
        data: {
            type: Object
        },
        options: {
            type: Object,
            default: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: [ChartDataLabels]
            }
        },

    }
}
</script>
