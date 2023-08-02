<template>
    <div class="my-3">
        <TextInput
            v-model="searchText"
            placeholder="Buscar..."
            class="w-full"
        />
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
        <h2 class="p-3" v-if="titleTable">{{titleTable}}</h2>
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 cursor-pointer" v-for="(col, key) in cols" @click="sort(key)">
                    <div class="flex items-center space-x-2">
                        <span>
                            {{col}}
                        </span>
                        <i class="fa-solid " :class="{
                        'fa-caret-up': orderSortAsc && columnSort == key,
                        'fa-caret-down': !orderSortAsc && columnSort == key
                    }"></i>
                    </div>

                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in formatedRows" class="bg-white border-b hover:bg-gray-50">
                <td
                    v-for="data in row"
                    class="px-6 py-4"
                >
                    <span v-if="['string', 'number'].includes(typeof data)">
                        {{data}}
                    </span>

                    <div
                        v-else-if="data.type == 'actions'"
                        class="text-right flex space-x-4"
                    >
                        <button
                            class="font-medium text-blue-600 hover:underline"
                            v-for="action in data.actions"
                            @click="action.onClick"
                            :title="action.title"
                        >
                            <i :class="action.classIcon"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr v-if="formatedRows.length == 0">
                <td class="px-6 py-4 text-center" :colspan="cols.length">
                    {{ isLoadingData ? 'Buscando Dados...' : 'Sem Resultados' }}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";

export default {
    components: {TextInput},
    props: {
        cols: {
            type: Array,
            default: []
        },
        rows: {
            type: Array,
            default: []
        },
        title: {
            type: String,
        },
        isLoadingData: {
            type: Boolean,
            default: false
        }
    },
    data(){
        return {
            columnSort: 0,
            orderSortAsc: true,
            searchText: ''
        }
    },
    methods: {
        sort(column)
        {
            if(this.columnSort == column) this.orderSortAsc = !this.orderSortAsc
            else {
                this.orderSortAsc = true
                this.columnSort = column
            }
        },
        reverseDate(date)
        {
            return date.split('/').reverse().join('-')
        }
    },
    computed:{
        formatedRows(){
            return this.rows
                .filter(row => {
                    return row
                        .filter(data =>
                            ['string'].includes(typeof data) &&
                            data.toLowerCase().indexOf(this.searchText.toLowerCase()) > -1
                        )
                        .length > 0
                })
                .sort((a, b) => {
                    // Verifica se está em ordenação crescente
                    const isAsc = this.orderSortAsc ? 1 : -1

                    // Verifica se é uma data (DD-MM-YYYY), usando Regex
                    const isDate = typeof a[this.columnSort] !== 'number' &&
                        a[this.columnSort]
                        .match(/[0-9]{2}[/][0-9]{2}[/][0-9]{4}/g)

                    if(isDate) // Caso seja uma data, converte as datas para formato YYYY-MM-DD
                        return isAsc * (this.reverseDate(a[this.columnSort]) > this.reverseDate(b[this.columnSort]) ? 1 : -1)

                    // Caso não seja uma data, compara String || Number
                    return isAsc * (a[this.columnSort] > b[this.columnSort] ? 1 : -1)
                })
        },
        titleTable()
        {
            return this.title ?? `Todos (${this.rows.length})`
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
