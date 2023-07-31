<template>
    <Radar :data="data" :options="options" />
</template>

<script>
// DataPage.vue
import { Radar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
} from 'chart.js'

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
)

export default {
    name: 'RadarChart',
    components: { Radar },
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
