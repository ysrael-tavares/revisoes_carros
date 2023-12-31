<template>
    <Modal :show="isVisible">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">{{owner.id ? "Edição" : "Cadastro"}} de Proprietários</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Preencha o formulário e {{owner.id ? "edite o" : "cadastro um novo"}}  proprietário
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
                        <option value="">Selecione o Gênero</option>
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
                        v-maska
                        data-maska="(##) #####-####"
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
                        @click="closeModalOwner"
                        class="bg-red-700 hover:bg-red-600">
                        Cancelar
                    </PrimaryButton>

                    <PrimaryButton :disabled="isLoading">
                        {{owner.id ? "Salvar" : "Cadastrar"}}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import {ownerDefault} from "@/Utils/Examples.js";
import { vMaska } from "maska"
import {mapActions} from "vuex";

export default {
    directives: { maska: vMaska },
    data: () => {
        return {
            owner: ownerDefault,
            errors: {},
            alert: "",
            isLoading: false
        }
    },
    props: {
        closeModal: {
            type: Function,
        },
    },
    methods: {
        ...mapActions('owner', ["closeModalOwner"]),
        sendOwner() { // Metodo para envio de proprietário
            if(this.isLoading) return

            this.isLoading = true
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
                    this.alert = response.data.message

                    setTimeout(() => {
                        this.$emit('updateOwner', response.data.content)
                        this.closeModalOwner()
                    }, 2000)
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
    updated() {
        this.owner = this.$store.state.owner.data
        this.clearAlerts()
    },
    computed: {
        isVisible(){
            return this.$store.state.owner.showModalInsertUpdate
        }
    },
    emits: ['updateOwner'],
    components: {InputSuccess, InputLabel, PrimaryButton, InputError, Modal, TextInput}

}
</script>

<style lang="scss" scoped>

</style>
