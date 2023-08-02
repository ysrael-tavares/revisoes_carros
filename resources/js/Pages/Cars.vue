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
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";

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
        PrimaryTable,
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
    },
    computed: {
        formatedCarList(){
            return this.carsList.map(car => {
                return [
                    car.brand.name,
                    car.model,
                    car.plate,
                    car.color,
                    car.year_of_manufacture,
                    car.owner.name,
                    car.revisions.length,
                    {
                        type: 'actions',
                        actions: [
                            {
                                title: 'Editar Carro',
                                classIcon: "fa-solid fa-pen-to-square",
                                onClick: () => this.editCar(car)
                            },
                            {
                                title: 'Nova Revisão',
                                classIcon: "fa-solid fa-clipboard-list",
                                onClick: () => this.newRevision(car)
                            }
                        ]
                    }
                ]
            })
        }
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

                        <PrimaryTable
                            :cols="['Marca','Modelo','Placa','Cor','Ano de Fabricação','Proprietário','Revisões','']"
                            :rows="formatedCarList"
                        />

                    </div>
                </div>
            </div>
        </div>
        <ModalNewCar
            :presetCar="car"
            :owner="car.owner"
            :showModal="showModal"
            :closeModal="closeModal"
            @updateRecords="getCars"
        />

        <ModalRevision
            :showModal="showModalRevision"
            :car="car"
            :closeModal="closeModalRevision"
            @updateRecords="getCars"
        />
    </AuthenticatedLayout>
</template>
