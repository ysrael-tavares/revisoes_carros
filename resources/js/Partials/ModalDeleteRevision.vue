<template>
    <Modal :show="isVisible" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Exclusão de Revisão</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir esta revisão?
                </p>
            </header>
            <form @submit.prevent="deleteRevision" class="mt-6 space-y-6">
                                <div class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="confirmDelete"
                        maxlength="4"
                        required
                    />

                    <span>
                        Estou ciente da exclusão da revisão do dia {{ dayRevision }}.
                    </span>
                </div>

                <div>
                    <InputSuccess :message="alert" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton
                        type="button"
                        @click="closeDeleteModal"
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
import {mapActions} from "vuex";

export default {
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
        revision(){
            return this.$store.state.revision.data
        },
        isVisible(){
            return this.$store.state.revision.showModalDelete
        },
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
        },
        dayRevision(){
            return moment(this.revision.review_day).format('DD/MM/YYYY')
        }
    },
    methods: {
        ...mapActions('revision', ['closeDeleteModal', 'viewRevisions']),
        ...mapActions('car', ['updateOwner', "updateCar"]),
        deleteRevision() { // Metodo para exclusão de revisão
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            axios.delete(route('revision.destroy', {revision: this.revision.id}))
                .then(response => {
                    this.alert = response.data.message

                    this.closeDeleteModal()

                    this.$emit('deleteRevision', response.data.content.revision_id)
                    this.$emit('updateOwner', response.data.content.owner)

                    const car = this.$store.state.revision.car

                    const updatedCar = {
                        ...car,
                        revisions: car.revisions.filter(rev => rev.id != response.data.content.revision_id)
                    }

                    this.updateOwner(response.data.content.owner)
                    this.updateCar(updatedCar)
                    this.viewRevisions(updatedCar)


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
