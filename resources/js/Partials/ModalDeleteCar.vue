<template>
    <Modal :show="showModal" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Exclusão de Carro</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir este carro?
                </p>
            </header>
            <form @submit.prevent="deleteCar" class="mt-6 space-y-6">
                <div class="mt-6">
                    <PrimaryTable
                        :cols="['Marca', 'Modelo', 'Placa', 'Cor', 'Ano de Fabricação']"
                        :rows="formatedCarData"
                        title="Carro"
                        :canSearch="false"
                    />
                </div>

                <div class="mt-6">
                    <PrimaryTable
                        :cols="['Data da revisão','Carro','Proprietário']"
                        :rows="formatedRevisionList"
                        :canSearch="false"
                        title="Revisões cadastradas"
                    />
                </div>

                <div class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="confirmDelete"
                        maxlength="4"
                        required
                    />

                    <span>
                        Estou ciente da exclusão dos dados listados acima.
                    </span>
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

                    <PrimaryButton :disabled="!confirmDelete" v-if="confirmDelete">
                        Excluir
                    </PrimaryButton>
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
import { plate } from "@/Utils/regex.js";
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";
import moment from "moment/moment.js";

export default {
    created(){
        this.brands = this.$page.props.brands
    },
    props: {
        showModal: {
            type: Boolean,
            default: false
        },
        car: {
            type: Object
        },
        closeModal: {
            type: Function
        },
    },
    data(){
      return {
          errors: {},
          alert: "",
          brands: [],
          isLoading: false,
          confirmDelete: false,
      }
    },
    updated() {
        this.confirmDelete = false
        this.clearAlerts()
    },
    computed:{
        formatedRevisionList()
        {
            return this.car.revisions
                .map(revision => {
                    return [
                        moment(revision.review_day).format('DD/MM/YYYY'),
                        this.carName,
                        this.car.owner.name,
                    ]
                })
        },
        formatedCarData()
        {
            return [
                [
                    this.car.brand.name,
                    this.car.model,
                    this.car.plate,
                    this.car.color,
                    this.car.year_of_manufacture,
                ]
            ]
        },
        carName(){
            return `${this.car.brand.name} ${this.car.model} ${this.car.year_of_manufacture}`
        }
    },
    methods: {
        deleteCar() { // Metodo para exclusão de carro
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            axios.delete(route('car.destroy', {car: this.car.id}))
                .then(response => {
                    this.alert = response.data.message

                    this.$emit('deleteCar', response.data.content.car_id)
                    this.$emit('updateOwner', response.data.content.owner)

                    setTimeout(this.closeModal, 1000)
                })
                .catch(erro => {
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        clearAlerts() { // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        },
    },
    emits: ['deleteCar', 'updateOwner'],
    components: {PrimaryTable, InputLabel, Modal, TextInput, InputSuccess, InputError, PrimaryButton}

}
</script>
