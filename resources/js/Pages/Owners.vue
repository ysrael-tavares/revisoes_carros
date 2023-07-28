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

export default {
    data: () => {
        return {
            owner: ownerDefault,
            ownersList: [],
            ownersListBySex: [],
            showModal: false,
            showModalCar: false,
            typeView: 'all'
        }
    },
    components: {
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
        }
    },
    created(){
        this.getOwners()
        this.getOwnersBySex()
    },
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
                                <PrimaryButton @click="showModal = true" type="button">
                                    + Proprietário
                                </PrimaryButton>
                            </div>
                        </header>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3" v-if="typeView == 'all'">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Proprietário
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Telefone
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Idade
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="owner in ownersList" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{owner.name}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{owner.email}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.phone}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.age}} Anos
                                        </td>
                                        <td class="px-6 py-4 text-right flex space-x-1">
                                            <button
                                                type="button"
                                                @click="editOwner(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Proprietário"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="newCar(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Cadastrar Carro"
                                            >
                                                <i class="fa-solid fa-car"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3 mb-3" v-if="typeView == 'by_sex'" v-for="(gender, index) in ownersListBySex">
                                <h2 class="p-3">{{index}}</h2>
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Proprietário
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Telefone
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Idade
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="owner in gender" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{owner.name}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{owner.email}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.phone}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.age}} Anos
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-between">
                                            <button
                                                type="button"
                                                @click="editOwner(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Proprietário"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="newCar(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Cadastrar Carro"
                                            >
                                                <i class="fa-solid fa-car"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <ModalOwner :showModal="showModal" :closeModal="closeModal" :presetOwner="owner" @updateRecords="getOwners" />
        <ModalNewCar :showModal="showModalCar" :owner="owner" :closeModal="closeModalCar" />
    </AuthenticatedLayout>
</template>
