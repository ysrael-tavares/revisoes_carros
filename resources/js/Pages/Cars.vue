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
import ModalDeleteCar from "@/Partials/ModalDeleteCar.vue";
import {mapActions} from "vuex";

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
            searchingData: false,
        }
    },
    components: {
        ModalDeleteCar,
        PrimaryTable,
        ModalRevision,
        Modal,
        TableData,
        InputSuccess,
        ModalNewCar,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        ...mapActions('car', ['prepareEditCar', 'prepareDeleteCar']),
        ...mapActions('revision', ['viewRevisions']),
        getCars(){ // Metódo para atualizar lista de carros
            if(this.searchingData) return
            this.searchingData = true

            axios
                .get(route('car.all'))
                .then(response => {
                    this.carsList = response.data
                })
                .finally(() => {
                    this.searchingData = false
                })
        },
        updateCar(carAffected){
            this.carsList = [
                ...this.carsList.filter(car => car.id != carAffected.id),
                carAffected
            ]

            if(this.car.id == carAffected.id) this.car = {...carAffected}
        },
        closeModalDeleteCar(car_id = null){
            this.car = {...defaultCar}

            this.carsList = this.carsList.filter(car => car.id != car_id)

            this.showModalDeleteCar = false
        }
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
                                onClick: () => this.prepareEditCar(car)
                            },
                            {
                                title: 'Nova Revisão',
                                classIcon: "fa-solid fa-clipboard-list",
                                onClick: () => this.viewRevisions(car)
                            },
                            {
                                title: 'Excluir Carro',
                                classIcon: "fa-solid fa-trash-can",
                                onClick: () => this.prepareDeleteCar(car)
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
                            :isLoadingData="searchingData"
                        />

                    </div>
                </div>
            </div>
        </div>
        <ModalNewCar
            :showAdditionalTable="false"
            @updateCar="updateCar"
        />

        <ModalRevision
            @updateCar="updateCar"
        />

        <ModalDeleteCar
            @deleteCar="closeModalDeleteCar"
        />
    </AuthenticatedLayout>
</template>
