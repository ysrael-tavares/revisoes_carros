<template>
    <Modal :show="showModal">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Nova Revisão</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Preencha o formulário e cadastre uma nova revisão no automóvel selecionado
                </p>
            </header>
            <form @submit.prevent="sendRevision" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Informação do Proprietário" />

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
                    <InputLabel value="Informações do Carro" />
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
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                        :max="new Date().toISOString().split('T')[0]"
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

                    <PrimaryButton>Cadastrar</PrimaryButton>
                </div>
            </form>
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

export default {
    props: {
        car: {
            type: Object,
            default: null,
        },
        closeModal: {
            type: Function,
        },
        showModal: {
            type: Boolean,
            default: false
        },
        presetRevision: {
            type: Object,
            default: null,
        }
    },
    data: () => {
        return {
            errors: {},
            alert: "",
            revision: {
                review_date: null
            }
        }
    },
    updated() {
        if(this.presetRevision?.id) this.revision = {...this.presetRevision}
        this.clearAlerts()
    },
    methods:{
        sendRevision()
        {
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
                    this.alert = response.data
                    setTimeout(this.closeModal, 2000)
                })
                .catch(erro => {
                    console.log(erro)
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.$emit('updateRecords')
                })
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        },
    },
    components: {InputSuccess, InputLabel, PrimaryButton, InputError, TableData, Modal, TextInput},
    emits: ['updateRecords'],

}
</script>

<style lang="scss" scoped>

</style>
