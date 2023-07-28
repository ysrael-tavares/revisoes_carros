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
import {defaultCar} from "@/Pages/Utils/Examples.js";

export default {
    props:{
      brands: {
          type: Array
      }
    },
    data: () => {
        return {
            car: {},
            errors: {},
            alert: "",
            carsList: [],
            showModal: false,
        }
    },
    components: {
        Modal,
        TableData,
        InputSuccess,
        ModalNewCar,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        sendCar() { // Metodo para envio de proprietário

            this.clearAlerts()

            // Processa a requisição
            axios
                .patch(route('car.update', {car: this.car.id}), this.car)
                .then(response => {
                    this.alert = response.data
                    setTimeout(this.closeModal, 2000)
                })
                .catch(erro => {
                    console.log(erro)
                    this.errors = erro.response.data.errors
                })
        },
        getCars(){ // Metódo para atualizar lista de carros
            axios
                .get(route('car.all'))
                .then(response => {
                    this.carsList = response.data
                })
        },
        editCar(car){ // Prepara a edição de um carro
            this.clearAlerts()
            this.car = {...car}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia o carro
            this.showModal = false
            this.car = {...defaultCar}
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        }
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Marcas</h2>
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
                                            0
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
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-4 sm:p-8 bg-white shadow">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Cadastro de Carros</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Preencha o formulário e cadastre um novo carro
                    </p>
                </header>
                <form @submit.prevent="sendCar" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Proprietário" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="car.owner.name"
                            required
                            :readonly="true"
                        />

                        <InputError class="mt-2" :message="errors.owner_id" />
                    </div>

                    <div>
                        <InputLabel for="brand" value="Marca/Montadora" />

                        <select
                            id="brand"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            v-model="car.brand_id"
                            required
                        >
                            <option :value="brand.id" v-for="brand in brands">{{brand.name}}</option>
                        </select>

                        <InputError class="mt-2" :message="errors.brand_id" />
                    </div>

                    <div>
                        <InputLabel for="model" value="Modelo" />

                        <TextInput
                            id="model"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="car.model"
                        />

                        <InputError class="mt-2" :message="errors.model" />
                    </div>

                    <div>
                        <InputLabel for="plate" value="Placa (Ex.: ABC1D23 ou ABC1234)" />

                        <TextInput
                            id="plate"
                            type="text"
                            class="mt-1 block w-full uppercase"
                            maxlength="7"
                            v-model="car.plate"
                        />

                        <InputError class="mt-2" :message="errors.plate" />
                    </div>

                    <div>
                        <InputLabel for="color" value="Cor" />

                        <TextInput
                            id="color"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="car.color"
                        />

                        <InputError class="mt-2" :message="errors.color" />
                    </div>

                    <div>
                        <InputLabel for="year_of_manufacture" value="Ano de Fabricação (YYYY)" />

                        <TextInput
                            id="year_of_manufacture"
                            type="text"
                            class="mt-1 block w-full"
                            maxlength="4"
                            v-model="car.year_of_manufacture"
                        />

                        <InputError class="mt-2" :message="errors.year_of_manufacture" />
                    </div>

                    <div>
                        <InputSuccess :message="alert" />
                    </div>

                    <div class="flex items-center gap-4">
                        <PrimaryButton
                            type="button"
                            @click="closeModal"
                            class="bg-red-700 hover:bg-red-600">
                            Cancelar
                        </PrimaryButton>

                        <PrimaryButton>Cadastrar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
