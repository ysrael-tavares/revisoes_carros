<template>
    <Modal :show="showModal" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">{{car.id ? "Edição" : "Cadastro"}} de Carros</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Preencha o formulário e {{car.id ? "edite o" : "cadastre um novo"}}  carro
                </p>
            </header>
            <div class="flex justify-between items-center mt-6">
                <div>
                    <InputLabel for="name" value="Proprietário" />

                    <span>
                        {{owner.name}}
                    </span>
                </div>

                <PrimaryButton @click="toggleRegister" v-if="showAdditionalTable">
                    {{ textButton }}
                </PrimaryButton>

                <InputError class="mt-2" :message="errors.owner_id" />
            </div>
            <form @submit.prevent="sendCar" class="mt-6 space-y-6" v-if="isRegister">
                <div>
                    <InputLabel for="brand" value="Marca/Montadora" />

                    <select
                        id="brand"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="car.brand_id"
                        required
                    >
                        <option value="0">Selecione a Marca</option>
                        <option :value="brand.id" v-for="brand in brands">{{brand.name}}</option>
                    </select>

                    <InputError class="mt-2" :message="errors.brand_id" />
                </div>

                <div>
                    <InputLabel for="model" value="Modelo" />

                    <div class="flex items-end">
                        <TextInput
                            id="model"
                            type="text"
                            class="mt-1 block w-full border-r-0 rounded-r-none"
                            v-model="car.model"
                            maxlength="30"
                            required
                        />

                        <span class="py-2 px-3 border border-gray-300 rounded-r-md" title="Caracteres Restantes">
                            {{30 - car.model.length}}
                        </span>
                    </div>

                    <InputError class="mt-2" :message="errors.model" />
                </div>

                <div>
                    <InputLabel for="plate" value="Placa (Ex.: ABC1D23 ou ABC1234)" />

                    <div class="flex items-end">
                        <TextInput
                            id="plate"
                            type="text"
                            class="mt-1 block w-full border-r-0 rounded-r-none uppercase"
                            v-model="car.plate"
                            maxlength="7"
                            required
                        />

                        <span class="py-2 px-3 border border-gray-300 rounded-r-md" title="Caracteres Restantes">
                            {{7 - car.plate.length}}
                        </span>
                    </div>

                    <InputError class="mt-2" :message="errors.plate" />
                </div>

                <div>
                    <InputLabel for="color" value="Cor" />

                    <div class="flex items-end">
                        <TextInput
                            id="color"
                            type="text"
                            class="mt-1 block w-full border-r-0 rounded-r-none"
                            v-model="car.color"
                            maxlength="20"
                            required
                        />

                        <span class="py-2 px-3 border border-gray-300 rounded-r-md" title="Caracteres Restantes">
                            {{20 - car.color.length}}
                        </span>
                    </div>

                    <InputError class="mt-2" :message="errors.color" />
                </div>

                <div>
                    <InputLabel for="year_of_manufacture" value="Ano de Fabricação (YYYY)" />

                    <div class="flex items-end">
                        <TextInput
                            id="year_of_manufacture"
                            type="text"
                            class="mt-1 block w-full border-r-0 rounded-r-none"
                            v-model="car.year_of_manufacture"
                            maxlength="4"
                            required
                        />

                        <span class="py-2 px-3 border border-gray-300 rounded-r-md" title="Caracteres Restantes">
                            {{4 - (isNaN(car.year_of_manufacture.length) ? 4 : car.year_of_manufacture.length)}}
                        </span>
                    </div>

                    <InputError class="mt-2" :message="errors.year_of_manufacture" />
                </div>

                <div>
                    <InputSuccess :message="alert" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton
                        v-if="!showAdditionalTable"
                        type="button"
                        @click="closeModal"
                        class="bg-red-700 hover:bg-red-600">
                        Cancelar
                    </PrimaryButton>

                    <PrimaryButton v-if="JSON.stringify(car) !== JSON.stringify(presetCar)" :disabled="isLoading">
                        {{car.id ? "Salvar" : "Cadastrar"}}
                    </PrimaryButton>
                </div>
            </form>
            <div class="mt-6 space-y-6" v-if="showAdditionalTable">
                <InputLabel value="Carros" />
                <PrimaryTable
                    :cols="['Marca','Modelo','Placa','Cor','Ano de Fabricação', 'Revisões','']"
                    :rows="formatedCarList"
                />
            </div>

            <div class="mt-6">
                <PrimaryButton
                    v-if="showAdditionalTable"
                    type="button"
                    @click="closeModal"
                    class="bg-red-700 hover:bg-red-600">
                    Fechar
                </PrimaryButton>
            </div>
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

export default {
    created(){
        this.brands = this.$page.props.brands
    },
    props: {
        presetCar: {
            type: Object,
            default: defaultCar
        },
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
        viewRevisions: {
            type: Function
        },
        defaultShowRegister: {
            type: Boolean,
            default: false
        },
        showAdditionalTable: {
            type: Boolean,
            default: true
        }
    },
    data(){
      return {
          errors: {},
          alert: "",
          car: defaultCar,
          brands: [],
          isLoading: false,
          isRegister: this.defaultShowRegister,
      }
    },
    updated() {
        this.car = {...this.presetCar}
        this.clearAlerts()
    },
    computed:{
        textButton(){
            return this.isRegister
                ? "Ocultar " : "Exibir " + (this.car.id ? "Edição" : "Cadastro")

        },
        formatedCarList(){
            return this.owner.cars?.map(car => {
                return [
                    car.brand.name,
                    car.model,
                    car.plate,
                    car.color,
                    car.year_of_manufacture,
                    car.revisions.length,
                    {
                        type: 'actions',
                        actions: [
                            {
                                title: 'Ver Revisões',
                                classIcon: "fa-solid fa-clipboard-list",
                                onClick: () => this.viewRevisions(car)
                            }
                        ]
                    }
                ]
            })
        }
    },
    methods: {
        sendCar() { // Metodo para envio de carro
            if(this.isLoading) return

            this.isLoading = true

            this.clearAlerts()

            this.car.owner_id = this.owner.id

            if(!this.validateData()) {
                this.isLoading = false
                return
            }

            const request = this.car.id
                ? axios
                    .patch(
                        route('car.update', {car: this.car.id}),
                        this.car
                    )
                : axios
                    .post(
                        route('car.store'),
                        this.car
                    )

            // Processa a requisição
            request
                .then(response => {
                    this.alert = response.data.message
                    this.car = {...defaultCar}

                    this.$emit('updateOwner', response.data.content.owner)
                    this.$emit('updateCar', response.data.content.car)
                })
                .catch(erro => {
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        validateData(){
            let numberErrors = 0

            // Validando Placa
            const isPlate = this.car.plate.match(plate)

            if(!isPlate)
            {
                this.errors.plate = 'Placa Inválida'
                numberErrors++
            }

            // Validando Ano
            const year = parseInt(this.car.year_of_manufacture)

            if(isNaN(year) || new Date().getFullYear() < year)
            {
                this.errors.year_of_manufacture = 'Ano Inválido'
                numberErrors++
            }

            return numberErrors === 0
        },
        clearAlerts() { // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        },
        toggleRegister(){
            this.isRegister = !this.isRegister
        }
    },
    emits: ['updateCar', 'updateOwner'],
    components: {PrimaryTable, InputLabel, Modal, TextInput, InputSuccess, InputError, PrimaryButton}

}
</script>
