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

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

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
                maintainAspectRatio: false
            }
        }
    }
}
</script>
