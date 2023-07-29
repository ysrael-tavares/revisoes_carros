<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";

export default {
    data: () => {
        return {
            brand: {name: ''},
            errors: {},
            alert: "",
            brandsList: [],
            showModal: false,
        }
    },
    components: {
        PrimaryTable,
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        sendBrand(){ // Metodo para envio de marca

            this.clearAlerts()

            // Verifica se é uma edição ou inserção
            const request = this.brand.id
                ? axios
                    .patch(
                        route('brand.update', {brand: this.brand.id}),
                        this.brand
                    )
                : axios
                    .post(
                        route('brand.store'),
                        this.brand
                    )

            // Processa a requisição
            request
                .then(response => {
                    this.alert = response.data
                    setTimeout(this.closeModal, 2000)
                })
                .catch(erro => {
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.getBrands()
                })
        },
        getBrands(){ // Metódo para atualizar lista de marcas
            axios
                .get(route('brand.all'))
                .then(response => {
                    this.brandsList = response.data
                })
        },
        editBrand(brand){ // Prepara a edição de um marca
            this.clearAlerts()
            this.brand = {...brand}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia o marca
            this.showModal = false
            this.brand = {name: ""}
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        }
    },
    created(){
        this.getBrands()
    },
    computed: {
        formatedBrandList(){
            return this.brandsList
                .map(brand => {
                    return [
                        brand.name,
                        brand.number_cars,
                        brand.total_revisions,
                        {
                            type: 'actions',
                            actions: [
                                {
                                    title: 'Editar Marca',
                                    classIcon: "fa-solid fa-pen-to-square",
                                    onClick: () => this.editBrand(brand)
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
    <Head title="Dashboard" />

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
                                <h2 class="text-lg font-medium text-gray-900">Lista de Marcas</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Marcas atuais
                                </p>
                            </div>
                            <div>
                                <PrimaryButton @click="showModal = true" type="button">
                                    + Marca
                                </PrimaryButton>
                            </div>
                        </header>

                        <PrimaryTable
                            :cols="['Nome', 'Carros', 'Revisões feitas', '']"
                            :rows="formatedBrandList"
                        />

                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-4 sm:p-8 bg-white shadow">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Cadastro de Marcas</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Preencha o formulário e cadastre uma nova marca
                    </p>
                </header>
                <form @submit.prevent="sendBrand" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Nome" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="brand.name"
                            required
                            autofocus
                        />

                        <InputError class="mt-2" :message="errors.name" />
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

                        <PrimaryButton :disabled="brand.processing">Cadastrar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
