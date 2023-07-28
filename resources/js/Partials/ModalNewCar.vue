<template>
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
                        v-model="owner.name"
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

                    <PrimaryButton :disabled="owner.processing">Cadastrar</PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {defaultCar} from "@/Utils/Examples.js";

export default {
    created(){
        this.brands = this.$page.props.brands
    },
    props: {
        showModal: {
            type: Boolean,
            default: false
        },
        owner: {
            type: Object
        },
        closeModal: {
            type: Function
        }
    },
    data(){
      return {
          errors: {},
          alert: "",
          car: {...defaultCar},
          brands: []
      }
    },
    methods: {
        sendCar() { // Metodo para envio de proprietário

            this.clearAlerts()

            this.car.owner_id = this.owner.id

            // Processa a requisição
            axios
                .post(route('car.store'), this.car)
                .then(response => {
                    this.alert = response.data
                    this.car = {...defaultCar}
                    setTimeout(this.closeModal, 2000)
                })
                .catch(erro => {
                    this.errors = erro.response.data.errors
                })
        },
        clearAlerts() { // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        },
    },
    components: {InputLabel, Modal, TextInput, InputSuccess, InputError, PrimaryButton}

}
</script>
