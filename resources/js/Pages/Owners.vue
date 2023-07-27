<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";

const ownerDefault = {
    name: '',
    gender: '',
    date_of_birth: '',
    email: '',
    phone: '',
}

const ownerExample = {
    name: 'Fulano',
    gender: 'Homem',
    date_of_birth: '1998-09-20',
    email: 'ysraeltavares33@gmail.com',
    phone: '83923456789',
}

export default {
    data: () => {
        return {
            owner: ownerDefault,
            errors: {},
            alert: "",
            ownersList: [],
            showModal: false,
        }
    },
    components: {
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        sendOwner(){ // Metodo para envio de proprietário

            this.clearAlerts()

            // Verifica se é uma edição ou inserção
            const request = this.owner.id
                ? axios
                    .patch(
                        route('owner.update', {owner: this.owner.id}),
                        this.owner
                    )
                : axios
                    .post(
                        route('owner.store'),
                        this.owner
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
                    this.getOwners()
                })
        },
        getOwners(){ // Metódo para atualizar lista de proprietários
            axios
                .get(route('owner.all'))
                .then(response => {
                    this.ownersList = response.data
                })
        },
        editOwner(owner){ // Prepara a edição de um proprietário
            this.clearAlerts()
            this.owner = {...owner}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia o proprietário
            this.showModal = false
            this.owner = ownerDefault
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        }
    },
    created(){
        this.getOwners()
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proprietários</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Proprietários</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Proprietários atuais
                                </p>
                            </div>
                            <div>
                                <PrimaryButton @click="showModal = true" type="button">
                                    + Proprietário
                                </PrimaryButton>
                            </div>
                        </header>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Proprietário
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Homem/Mulher
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Telefone
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Idade
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="owner in ownersList" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{owner.name}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{owner.gender}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.email}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.phone}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{owner.age}} Anos
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-between">
                                            <button
                                                type="button"
                                                @click="editOwner(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Proprietário"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="editOwner(owner)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Cadastrar Carro"
                                            >
                                                <i class="fa-solid fa-car"></i>
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
                    <h2 class="text-lg font-medium text-gray-900">Cadastro de Proprietários</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Preencha o formulário e cadastre um novo proprietário
                    </p>
                </header>
                <form @submit.prevent="sendOwner" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Nome" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="owner.name"
                            required
                            autofocus
                        />

                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div>
                        <InputLabel for="date_of_birth" value="Data de Nascimento" />

                        <input
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                            id="date_of_birth"
                            type="date"
                            :max="new Date().toISOString().split('T')[0]"
                            v-model="owner.date_of_birth"
                            required
                        />

                        <InputError class="mt-2" :message="errors.date_of_birth" />
                    </div>

                    <div>
                        <InputLabel for="gender" value="Homem/Mulher" />

                        <select
                            id="gender"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            v-model="owner.gender"
                            required
                        >
                            <option value="Homem">Homem</option>
                            <option value="Mulher">Mulher</option>
                        </select>

                        <InputError class="mt-2" :message="errors.gender" />
                    </div>

                    <div>
                        <InputLabel for="phone" value="Telefone" />

                        <TextInput
                            id="phone"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="owner.phone"
                        />

                        <InputError class="mt-2" :message="errors.phone" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="owner.email"
                        />

                        <InputError class="mt-2" :message="errors.email" />
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
    </AuthenticatedLayout>
</template>
