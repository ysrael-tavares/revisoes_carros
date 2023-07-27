<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";

const ownerDefault = {
    name: '',
    gender: '',
    date_of_birth: '',
    email: '',
    phone: '',
}

export default {
    data: () => {
        return {
            owner: {
                name: 'Fulano',
                gender: 'Homem',
                date_of_birth: '1998-09-20',
                email: 'ysraeltavares33@gmail.com',
                phone: '83923456789',
            },
            errors: {},
            alert: ""
        }
    },
    components: {
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        sendOwner(){
            this.alert = ""
            this.errors = {}

            axios
                .post(route('owner.store'), this.owner)
                .then(response => {
                    this.owner = ownerDefault
                    this.alert = response.data
                })
                .catch(erro => {
                    console.log(erro.response)
                    this.errors = erro.response.data.errors
                })
        }
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
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
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
                                <PrimaryButton :disabled="owner.processing">Cadastrar</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="owner.recentlySuccessful" class="text-sm text-gray-600">Salvo</p>
                                </Transition>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
