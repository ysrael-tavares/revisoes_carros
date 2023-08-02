<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ModalNewCar from "@/Partials/ModalNewCar.vue";
import ModalOwner from "@/Partials/ModalOwner.vue";
import {defaultCar, defaultRevision, ownerDefault} from "@/Utils/Examples.js";
import PieChart from '@/Components/Charts/PieChart.vue'
import LineChart from '@/Components/Charts/LineChart.vue'
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";
import RadarChart from "@/Components/Charts/RadarChart.vue";
import moment from "moment";
import ModalRevision from "@/Partials/ModalRevision.vue";
import ModalViewRevisions from "@/Partials/ModalViewRevisions.vue";
import ModalDeleteCar from "@/Partials/ModalDeleteCar.vue";
import ModalDeleteRevision from "@/Partials/ModalDeleteRevision.vue";
import ModalDeleteOwner from "@/Partials/ModalDeleteOwner.vue";

export default {
    data: () => {
        return {
            owner: {...ownerDefault},
            car: {...defaultCar},
            revision: {...defaultRevision},
            ownersList: [],
            ownersListBySex: [],
            showModal: false,
            showModalCar: false,
            showModalViewCars: false,
            showModalRevision: false,
            showModalViewRevisions: false,
            showModalDeleteCar: false,
            showModalDeleteRevision: false,
            showModalDeleteOwner: false,
            searchingData: false,
            typeView: 'all',
            searchText: "",
            grid: null,
            columns: [
                "Nome",
                "Idade",
                "Carros",
                "Revisões",
                "Intervalo Médio de Revisão",
                "Previsão de Revisão",
                ""
            ]
        }
    },
    components: {
        ModalDeleteRevision,
        ModalViewRevisions,
        ModalRevision,
        RadarChart,
        PrimaryTable,
        PieChart,
        LineChart,
        ModalOwner,
        ModalNewCar,
        ModalDeleteCar,
        ModalDeleteOwner,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        getOwners() { // Metódo para atualizar lista de proprietários
            if(this.searchingData) return
            this.searchingData = true

            axios
                .get(route('owner.all'))
                .then(response => {
                    this.ownersList = response.data
                })
                .finally(() => {
                    this.searchingData = false
                })
        },
        getOwnersBySex() { // Metódo para atualizar lista de proprietários
            axios
                .get(route('owner.all_by_sex'))
                .then(response => {
                    this.ownersListBySex = response.data
                })
        },
        editOwner(owner) { // Prepara a edição de um proprietário
            this.owner = {...owner}
            this.showModal = true
        },
        updateOwner(ownerAffected){
            this.ownersList = [
                ...this.ownersList.filter(owner => owner.id != ownerAffected.id),
                ownerAffected
            ]

            if(this.owner.id == ownerAffected.id) this.owner = {...ownerAffected}

            const selectedCar = this.owner.cars?.find(car => car.id == this.car.id)

            if(selectedCar) this.car = {...selectedCar, owner: {...ownerAffected}}

            this.getOwnersBySex()
        },
        clearData(){
            this.owner = {...ownerDefault}
        },
        closeModal() { // Fecha o modal e esvazia o proprietário
            this.showModal = false
            this.owner = ownerDefault
        },
        newCar(owner) { // Prepara a edição de um proprietário
            this.owner = {...owner}
            this.showModalCar = true
        },
        closeModalCar() { // Fecha o modal e esvazia o proprietário
            this.showModalCar = false
        },
        toggleView()
        {
            this.typeView = this.typeView == 'all' ? 'by_sex' : 'all'
        },
        newOwner()
        {
            this.owner = {...ownerDefault}
            this.showModal = true
        },
        returnFormatedRows(rows)
        {
            return rows
                .map(owner => {
                    return [
                        owner.name,
                        owner.age,
                        owner.cars.length,
                        owner.revisions.length,
                        owner.average_interval_revision + ' dias',
                        owner.next_revision ? moment(owner.next_revision).format('DD/MM/YYYY') : 'Sem Previsão',
                        {
                            type: 'actions',
                            actions: [
                                {
                                    title: 'Carros',
                                    classIcon: "fa-solid fa-car",
                                    onClick: () => this.newCar(owner)
                                },
                                {
                                    title: 'Editar Proprietário',
                                    classIcon: "fa-solid fa-pen-to-square",
                                    onClick: () => this.editOwner(owner)
                                },
                                {
                                    title: 'Excluir Proprietário',
                                    classIcon: "fa-solid fa-trash-can",
                                    onClick: () => this.deleteOwner(owner)
                                },
                            ]
                        }
                    ]
                })
        },
        closeModalRevision() { // Fecha o modal e esvazia o proprietário
            this.showModalRevision = false
            this.showModalCar = true
        },
        viewRevisions(car){
            this.car = {
                ...car,
                owner: {...this.owner}
            }
            this.showModalCar = false
            this.showModalRevision = true
        },
        deleteCar(car){
            this.car = {...car, owner: this.owner}

            this.showModalViewCars = false
            this.showModalDeleteCar = true
        },
        closeModalDeleteCar(){
            this.car = {...defaultCar}

            this.showModalViewCars = true
            this.showModalDeleteCar = false
        },
        deleteRevision(revision){
            this.revision = {
                ...revision,
                car: {
                    ...this.car
                }
            }

            this.showModalRevision = false
            this.showModalDeleteRevision = true
        },
        closeModalDeleteRevision(){
            this.revision = {...defaultRevision}

            this.showModalRevision = true
            this.showModalDeleteRevision = false
        },
        deleteOwner(owner)
        {
            this.owner = {...owner}
            this.showModalDeleteOwner = true
        },
        closeModalDeleteOwner(owner_id = null)
        {
            this.owner = {...ownerDefault}

            this.ownersList = this.ownersList.filter(owner => owner.id != owner_id)
            if(owner_id) this.getOwnersBySex()

            this.showModalDeleteOwner = false
        }
    },
    created(){
        this.getOwners()
        this.getOwnersBySex()
    },
    mounted() {
    },
    computed: {
        formatedOwnerList(){
            return this.returnFormatedRows(this.ownersList)
        },
        optionsChart(){
            return {
                responsive: true,
                maintainAspectRatio: false
            }
        },
        dataChartGender(){
            const listGender = this.ownersListBySex[0]
            const numberMen = listGender?.Homem?.length ?? 0
            const numberWomen = listGender?.Mulher?.length ?? 0

            return {
                labels: ['Homens', 'Mulheres'],
                datasets: [
                    {
                        label: 'Proprietários(as)',
                        backgroundColor: ['#1E6495', '#FC036C'],
                        data: [numberMen, numberWomen],
                        datalabels: {
                            color: '#FFF'
                        }
                    }
                ]
            }
        },
        dataChartAge()
        {
            const listGender = this.ownersListBySex[0]

            let ageList = []

            let result = this.ownersList.map(owner => owner.age)
            result.sort()
            result.forEach((atualAge) => {
                const age = ageList.find(age => atualAge == age.number)

                if(!age)
                {
                    ageList.push({
                        number: atualAge,
                        count: 1
                    })
                }else
                {
                    age.count++
                }
            })

            return {
                labels: ageList.map(age => age.number + ' anos'),
                datasets: [
                    {
                        label: 'Proprietários',
                        backgroundColor: ['#1E6495'],
                        data: ageList.map(age => age.count),
                        datalabels: {
                            color: '#FFF'
                        }
                    }
                ]
            }
        },
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proprietários</h2>
        </template>

        <div class="py-12 space-y-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Estátisticas de Proprietários</h2>
                            </div>
                        </header>

                        <div class="flex flex-col md:flex-row sm:justify-around md:h-48">
                            <div>
                                <PieChart :data="dataChartGender" :options="optionsChart" />
                            </div>
                            <div>
                                <LineChart :data="dataChartAge" :options="optionsChart" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Proprietários</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Proprietários atuais
                                </p>
                            </div>
                            <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:justify-between md:items-center md:space-x-3">
                                <PrimaryButton @click="toggleView" type="button">
                                    {{ typeView == 'all' ? 'Classificar por Sexo' : 'Exibição Padrão' }}
                                </PrimaryButton>

                                <PrimaryButton @click="newOwner" type="button">
                                    + Proprietário
                                </PrimaryButton>
                            </div>
                        </header>

                        <PrimaryTable
                            :cols='columns'
                            :rows="formatedOwnerList"
                            v-if="typeView == 'all'"
                            :isLoadingData="searchingData"
                            :title="`Todos os Usuários (${formatedOwnerList.length})`"
                        />

                        <PrimaryTable
                            v-if="typeView == 'by_sex'"
                            v-for="(gender, index) in ownersListBySex[0]"
                            :cols='columns'
                            :rows="returnFormatedRows(gender)"
                            :title="`${index} (${gender.length}) - Média de Idade: ${ Math.floor(ownersListBySex[1][index]) } anos`"
                        />
                    </div>
                </div>
            </div>
        </div>

        <ModalOwner
            :showModal="showModal"
            :closeModal="closeModal"
            :presetOwner="owner"
            @updateOwner="updateOwner"
            @clearData="clearData"
        />
        <ModalNewCar
            :showModal="showModalCar"
            :owner="owner"
            :closeModal="closeModalCar"
            :viewRevisions="viewRevisions"
            :deleteCar="deleteCar"
            @updateOwner="updateOwner"
        />
        <ModalRevision
            :showModal="showModalRevision"
            :car="car"
            :closeModal="closeModalRevision"
            :deleteRevision="deleteRevision"
            @updateOwner="updateOwner"
        />

        <ModalDeleteCar
            :showModal="showModalDeleteCar"
            :car="car"
            :closeModal="closeModalDeleteCar"
            @deleteCar="closeModalDeleteCar"
            @updateOwner="updateOwner"
        />

        <ModalDeleteRevision
            :showModal="showModalDeleteRevision"
            :revision="revision"
            :closeModal="closeModalDeleteRevision"
            @updateOwner="updateOwner"
            @deleteRevision="closeModalDeleteRevision"
        />

        <ModalDeleteOwner
            :showModal="showModalDeleteOwner"
            :owner="owner"
            :closeModal="closeModalDeleteOwner"
            @deleteOwner="closeModalDeleteOwner"
        />
    </AuthenticatedLayout>
</template>
