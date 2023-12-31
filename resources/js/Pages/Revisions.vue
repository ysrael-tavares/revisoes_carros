<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";
import moment from "moment";
import ModalRevision from "@/Partials/ModalRevision.vue";
import {defaultRevision} from "@/Utils/Examples.js";
import PrimaryTable from "@/Components/Table/PrimaryTable.vue";
import ModalDeleteRevision from "@/Partials/ModalDeleteRevision.vue";
import {mapActions} from "vuex";

export default {
    data: () => {
        return {
            revision: {...defaultRevision},
            revisionsList: [],
            searchingData: false,
        }
    },
    components: {
        ModalDeleteRevision,
        PrimaryTable,
        ModalRevision,
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        ...mapActions('revision', ['prepareEditRevision', 'viewRevisions', 'prepareDeleteRevision']),
        getRevisions(){ // Metódo para atualizar lista de revisões
            if(this.searchingData) return
            this.searchingData = true

            axios
                .get(route('revision.all'))
                .then(response => {
                    this.revisionsList = response.data
                })
                .finally(() => {
                    this.searchingData = false
                })
        },
        editRevision(revision){ // Prepara a edição de um revisão
            this.prepareEditRevision(revision)
            this.viewRevisions(revision.car)
        },
        updateRevision(revisionAffected)
        {
            this.revisionsList = [
                ...this.revisionsList.filter(revision => revision.id != revisionAffected.id),
                revisionAffected
            ]

            if(this.revision.id == revisionAffected.id) this.revision = {...revisionAffected}
        },
        closeModalDeleteRevision(revision_id = null){
            this.revision = {...defaultRevision}

            this.revisionsList = this.revisionsList.filter(revision => revision.id != revision_id)

            this.showModalDeleteRevision = false
        },
    },
    created(){
        this.getRevisions()
    },
    computed: {
        formatedRevisionList()
        {
            return this.revisionsList
                .map(revision => {
                    return [
                        moment(revision.review_day).format('DD/MM/YYYY'),
                        `${revision.car.brand.name} ${revision.car.model} ${revision.car.year_of_manufacture}`,
                        revision.car.owner.name,
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
    <Head title="Revisões" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Revisões</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Revisões</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Revisões atuais
                                </p>
                            </div>
                        </header>

                        <PrimaryTable
                            :cols="['Data da revisão','Carro','Proprietário','']"
                            :rows="formatedRevisionList"
                            :isLoadingData="searchingData"
                        />

                    </div>
                </div>
            </div>
        </div>
        <ModalRevision
            @updateRevision="updateRevision"
        />

        <ModalDeleteRevision
            @deleteRevision="closeModalDeleteRevision"
        />
    </AuthenticatedLayout>
</template>
