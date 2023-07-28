<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import TableData from "@/Components/Table/TableData.vue";
import ModalNewCar from "@/Partials/ModalNewCar.vue";
import Modal from "@/Components/Modal.vue";
import {defaultCar} from "@/Utils/Examples.js";
import ModalRevision from "@/Partials/ModalRevision.vue";

export default {
    props:{
      brands: {
          type: Array
      }
    },
    data: () => {
        return {
            car: {},
            carsList: [],
            showModal: false,
            showModalRevision: false,
        }
    },
    components: {
        ModalRevision,
        Modal,
        TableData,
        InputSuccess,
        ModalNewCar,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        getCars(){ // Metódo para atualizar lista de carros
            axios
                .get(route('car.all'))
                .then(response => {
                    this.carsList = response.data
                })
        },
        editCar(car){ // Prepara a edição de um carro
            this.car = {...car}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia o carro
            this.showModal = false
            this.car = {...defaultCar}
        },
        newRevision(car){ // Prepara a edição de um carro
            this.car = {...car}
            this.showModalRevision = true
        },
        closeModalRevision(){ // Fecha o modal e esvazia o carro
            this.showModalRevision = false
            this.car = {...defaultCar}
        },
    },
    created(){
        this.getCars()
    }
}
</script>

<template>
    <Head title="Carros" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Carros</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Carros</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Carros Cadastrados
                                </p>
                            </div>
                        </header>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Marca
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Modelo
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Placa
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Cor
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Ano de Fabricação
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Proprietário
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Revisões
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="car in carsList" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <TableData>
                                            {{car.brand.name}}
                                        </TableData>
                                        <TableData>
                                            {{car.model}}
                                        </TableData>
                                        <TableData>
                                            {{car.plate}}
                                        </TableData>
                                        <TableData>
                                            {{car.color}}
                                        </TableData>
                                        <TableData>
                                            {{car.year_of_manufacture}}
                                        </TableData>
                                        <TableData>
                                            {{car.owner.name}}
                                        </TableData>
                                        <TableData>
                                            {{car.revisions.length}}
                                        </TableData>
                                        <td class="px-6 py-4 text-right flex justify-between">
                                            <button
                                                type="button"
                                                @click="editCar(car)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Marca"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="newRevision(car)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Marca"
                                            >
                                                <i class="fa-solid fa-clipboard-list"></i>
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
        <ModalNewCar
            :presetCar="car"
            :owner="car.owner"
            :showModal="showModal"
            :closeModal="closeModal"
            @updateRecord="getCars"
        />

        <ModalRevision
            :showModal="showModalRevision"
            :car="car"
            :closeModal="closeModalRevision"
        />
    </AuthenticatedLayout>
</template>
