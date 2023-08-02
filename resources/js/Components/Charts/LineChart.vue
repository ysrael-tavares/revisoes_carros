<template>
    <Bar :data="data" :options="options" />
</template>

<script>
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import ChartDataLabels from 'chartjs-plugin-datalabels'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ChartDataLabels)

export default {
    name: 'BarChart',
    components: { Bar },
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
        }
    }
}
</script>
