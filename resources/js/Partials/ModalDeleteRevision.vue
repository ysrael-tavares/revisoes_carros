<template>
    <Modal :show="showModal" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Exclusão de Revisão</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir esta revisão?
                </p>
            </header>
            <form @submit.prevent="deleteRevision" class="mt-6 space-y-6">
                <div class="mt-6">
                    <PrimaryTable
                        :cols="['Data da revisão','Carro','Proprietário']"
                        :rows="formatedRevision"
                        title="Revisão"
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
    props: {
        showModal: {
            type: Boolean,
            default: false
        },
        revision: {
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
        formatedRevision()
        {
            return [
                [
                    moment(this.revision.review_day).format('DD/MM/YYYY'),
                    this.carName,
                    this.revision.car.owner.name,
                ]
            ]
        },
        carName(){
            return `${this.revision.car.brand.name} ${this.revision.car.model} ${this.revision.car.year_of_manufacture}`
        }
    },
    methods: {
        deleteRevision() { // Metodo para exclusão de revisão
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            axios.delete(route('revision.destroy', {revision: this.revision.id}))
                .then(response => {
                    this.alert = response.data.message

                    this.$emit('deleteRevision', response.data.content.revision_id)
                    this.$emit('updateOwner', response.data.content.owner)
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
    emits: ['deleteRevision', 'updateOwner'],
    components: {PrimaryTable, InputLabel, Modal, TextInput, InputSuccess, InputError, PrimaryButton}

}
</script>
