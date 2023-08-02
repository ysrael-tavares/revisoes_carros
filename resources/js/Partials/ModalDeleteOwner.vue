<template>
    <Modal :show="showModal" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Exclusão de Proprietário</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir este proprietário?
                </p>
            </header>
            <form @submit.prevent="deleteOwner" class="mt-6 space-y-6">
                <div class="mt-6">
                    <PrimaryTable
                        :cols='["Nome", "Email", "Idade", "Carros", "Revisões",]'
                        :rows="formatedOwnerData"
                        title="Proprietário"
                        :canSearch="false"
                    />
                </div>

                <div class="mt-6">
                    <PrimaryTable
                        :cols="['Marca', 'Modelo', 'Placa', 'Cor', 'Ano de Fabricação']"
                        :rows="formatedCarList"
                        title="Carros"
                        :canSearch="false"
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
        owner: {
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
        formatedCarList()
        {
            return this.owner.cars
                .map(car => {
                    return [
                        car.brand.name,
                        car.model,
                        car.plate,
                        car.color,
                        car.year_of_manufacture,
                    ]
                })
        },
        formatedOwnerData()
        {
            return [
                [
                    this.owner.name,
                    this.owner.email,
                    this.owner.age,
                    this.owner.cars.length,
                    this.owner.revisions.length,
                ]
            ]
        },
        carName(){
            return `${this.car.brand.name} ${this.car.model} ${this.car.year_of_manufacture}`
        }
    },
    methods: {
        deleteOwner() { // Metodo para exclusão de carro
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            axios.delete(route('owner.destroy', {owner: this.owner.id}))
                .then(response => {
                    this.alert = response.data.message

                    this.$emit('deleteOwner', response.data.content.owner_id)
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
    emits: ['deleteOwner'],
    components: {PrimaryTable, InputLabel, Modal, TextInput, InputSuccess, InputError, PrimaryButton}

}
</script>
