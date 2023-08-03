<template>
    <Modal :show="isVisible">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">{{revision.id ? "Edição" : "Cadastro"}} de Revisão</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Preencha o formulário e {{revision.id ? "edite a" : "cadastre uma nova"}}  revisão
                </p>
            </header>
            <form @submit.prevent="sendRevision" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Informação do Proprietário" />

                    <span>{{car?.owner?.name}}</span>

                    <InputError class="mt-2" :message="errors.owner_id" />
                </div>

                <div>
                    <InputLabel value="Informações do Carro" />
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b hover:bg-gray-50">
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
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <InputLabel for="name" value="Data da Revisão" />

                    <input
                        id="name"
                        type="date"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                        v-model="revision.review_day"
                        required
                    />

                    <InputError class="mt-2" :message="errors.owner_id" />
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

                    <PrimaryButton v-if="canSave">
                        {{revision.id ? "Salvar" : "Cadastrar"}}
                    </PrimaryButton>
                </div>
            </form>

            <div class="mt-6">
                <InputLabel value="Revisões Cadastradas" />
                <PrimaryTable
                    :cols="['Data da revisão','Carro', '']"
                    :rows="formatedRevisionList"
                />
            </div>
        </div>
    </Modal>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import TableData from "@/Components/Table/TableData.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import {defaultRevision} from "@/Utils/Examples.js";
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";
import moment from "moment";
import {mapActions} from "vuex";

export default {
    data: () => {
        return {
            errors: {},
            alert: "",
            revision: {...defaultRevision},
            isLoading: false,
            initialRevision: {...defaultRevision}
        }
    },
    updated() {
        this.refreshRevision()
        this.clearAlerts()
    },
    methods:{
        ...mapActions('revision', ['closeModalRevision', 'prepareEditRevision', 'viewRevisions', 'prepareDeleteRevision']),
        ...mapActions('car', ['updateOwner']),
        sendRevision()
        {
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            this.revision.car_id = this.car.id

            // Verifica se é uma edição ou inserção
            const request = this.revision.id
                ? axios
                    .patch(
                        route('revision.update', {revision: this.revision.id}),
                        this.revision
                    )
                : axios
                    .post(
                        route('revision.store'),
                        this.revision
                    )

            // Processa a requisição
            request
                .then(response => {
                    this.alert = response.data.message
                    this.revision = defaultRevision

                    this.$emit('updateOwner', response.data.content.car.owner)
                    this.$emit('updateCar', response.data.content.car)
                    this.$emit('updateRevision', response.data.content)

                    this.updateOwner(response.data.content.car.owner)
                    this.viewRevisions(response.data.content.car)
                })
                .catch(erro => {
                    console.log(erro)
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        },
        editRevision(revision){
            this.prepareEditRevision(revision)
            this.refreshRevision()
        },
        refreshRevision()
        {
            this.revision = this.$store.state.revision.data
            //this.initialRevision = this.$store.state.revision.data
        },
        closeModal(){
            this.closeModalRevision()
        }
    },
    computed: {
        car(){
            return this.$store.state.revision.car
        },
        isVisible(){
            return this.$store.state.revision.showModalInsertUpdate
        },
        canSave(){
            return JSON.stringify(this.initialRevision) != JSON.stringify(this.revision)
        },
        formatedRevisionList()
        {
            return this.car.revisions
                .map(revision => {
                    return [
                        moment(revision.review_day).format('DD/MM/YYYY'),
                        this.carName,
                        {
                            type: 'actions',
                            actions: [
                                {
                                    title: 'Editar Revisão',
                                    classIcon: "fa-solid fa-pen-to-square",
                                    onClick: () => this.editRevision(revision)
                                },
                                {
                                    title: 'Excluir Revisão',
                                    classIcon: "fa-solid fa-trash-can",
                                    onClick: () => this.prepareDeleteRevision(revision)
                                },
                            ]
                        }
                    ]
                })
        },
        carName(){
            return `${this.car.brand.name} ${this.car.model} ${this.car.year_of_manufacture}`
        }
    },
    components: {PrimaryTable, InputSuccess, InputLabel, PrimaryButton, InputError, TableData, Modal, TextInput},
    emits: ['updateOwner', 'updateCar', 'updateRevision'],

}
</script>

<style lang="scss" scoped>

</style>
