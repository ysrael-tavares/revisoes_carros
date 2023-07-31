<template>
    <Pie :data="data" :options="options" />
</template>

<script>
// DataPage.vue
import { Pie } from 'vue-chartjs'
import { Chart, ArcElement, Tooltip, Legend, Title} from 'chart.js'

Chart.register(Title, Tooltip, Legend, ArcElement)

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
                maintainAspectRatio: false
            }
        }
    }
}
</script>
