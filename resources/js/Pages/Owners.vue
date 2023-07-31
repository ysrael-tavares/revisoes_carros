<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";
import ModalNewCar from "@/Partials/ModalNewCar.vue";
import ModalOwner from "@/Partials/ModalOwner.vue";
import {ownerDefault} from "@/Utils/Examples.js";
import Chart from '@/Components/Charts/Chart.vue'
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";

export default {
    data: () => {
        return {
            owner: ownerDefault,
            ownersList: [],
            ownersListBySex: [],
            showModal: false,
            showModalCar: false,
            typeView: 'all',
            searchText: "",
            grid: null,
        }
    },
    components: {
        PrimaryTable,
        Chart,
        ModalOwner,
        ModalNewCar,
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        getOwners() { // Metódo para atualizar lista de proprietários
            axios
                .get(route('owner.all'))
                .then(response => {
                    this.ownersList = response.data
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
            this.owner = ownerDefault
            this.showModal = true
        },
        returnFormatedRows(rows)
        {
            return rows
                .map(owner => {
                    return [
                        owner.name,
                        owner.email,
                        owner.phone,
                        owner.age,
                        {
                            type: 'actions',
                            actions: [
                                {
                                    title: 'Editar Proprietário',
                                    classIcon: "fa-solid fa-pen-to-square",
                                    onClick: () => this.editOwner(owner)
                                },
                                {
                                    title: 'Cadastrar Carro',
                                    classIcon: "fa-solid fa-car",
                                    onClick: () => this.newCar(owner)
                                },
                            ]
                        }
                    ]
                })
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
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proprietários</h2>
        </template>

        <div class="py-12">
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
                            :cols='["Nome", "Email", "Telefone", "Idade", ""]'
                            :rows="formatedOwnerList"
                            v-if="typeView == 'all'"
                        />

                        <PrimaryTable
                            v-if="typeView == 'by_sex'"
                            v-for="(gender, index) in ownersListBySex[0]"
                            :cols='["Nome", "Email", "Telefone", "Idade", ""]'
                            :rows="returnFormatedRows(gender)"
                            :title="`${index} - Média de Idade: ${ Math.floor(ownersListBySex[1][index]) } anos`"
                        />
                    </div>
                </div>
            </div>
        </div>

        <ModalOwner :showModal="showModal" :closeModal="closeModal" :presetOwner="owner" @updateRecords="getOwners" />
        <ModalNewCar :showModal="showModalCar" :owner="owner" :closeModal="closeModalCar" />
    </AuthenticatedLayout>
</template>
