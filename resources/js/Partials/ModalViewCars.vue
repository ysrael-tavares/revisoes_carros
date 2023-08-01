<template>
    <Modal :show="showModal" maxWidth="5xl">
        <div class="p-4 sm:p-8 bg-white shadow">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Listagem de Carros</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Confira os carros do proprietário selecionado
                </p>
            </header>
            <div class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Proprietário" />

                    <span>{{owner.name}}</span>
                </div>

                <div>
                    <InputLabel value="Carros" />
                    <PrimaryTable
                        :cols="['Marca','Modelo','Placa','Cor','Ano de Fabricação', 'Revisões','']"
                        :rows="formatedCarList"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton
                        type="button"
                        @click="closeModal"
                        class="bg-red-700 hover:bg-red-600">
                        Fechar
                    </PrimaryButton>
                </div>
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

export default {
    props: {
        owner: {
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
        newRevision: {
            type: Function
        }
    },
    computed: {
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
                                title: 'Nova Revisão',
                                classIcon: "fa-solid fa-circle-plus",
                                onClick: () => this.newRevision(car)
                            }
                        ]
                    }
                ]
            })
        }
    },
    components: {PrimaryTable, InputSuccess, InputLabel, PrimaryButton, InputError, TableData, Modal, TextInput},
}
</script>

<style lang="scss" scoped>

</style>
