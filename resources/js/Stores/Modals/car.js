import {defaultCar, ownerDefault} from "@/Utils/Examples.js";

const state = () => ({
    showModalInsertUpdate: false,
    showFormInsertUpdate: false,
    showModalDelete: false,
    owner: {...ownerDefault},
    data: {...defaultCar},
    list: []
})

const getters = {

}

const actions = {
    showModalCar({state, commit}, owner){
        commit('showModal')
        commit('updateOwner', owner)
    },
    closeModalCar({state, commit}){
        commit('updateCar', defaultCar)
        commit('closeModal')
        commit('closeForm')
    },
    prepareEditCar({state, commit}, car) {
        if(car.owner) commit('updateOwner', car.owner)

        commit('updateCar', car)
        commit('showModal')
        commit('showForm')
    },
    updateOwner({state, commit}, owner) {
        commit('updateOwner', owner)
    },
    showFormCar({state, commit}, owner){
        commit('showForm')
    },
    closeFormCar({state, commit}){
        commit('closeForm')
    },
    clearFormCar({state, commit}){
        commit('updateCar', defaultCar)
    },
    prepareDeleteCar({state, commit}, car) {
        if(car.owner) commit('updateOwner', car.owner)

        commit('updateCar', car)
        commit('showModalDelete')
    },
    closeModalDeleteCar({state, commit}){
        commit('closeModalDelete')
    },
}

const mutations = {
    showModal(state){
        state.showModalInsertUpdate = true
    },
    closeModal(state){
        state.showModalInsertUpdate = false
    },
    showForm(state){
        state.showFormInsertUpdate = true
    },
    closeForm(state){
        state.showFormInsertUpdate = false
    },
    updateCar(state, car){
        state.data = {...car}
    },
    updateOwner(state, owner){
        state.owner = {...owner}
    },
    showModalDelete(state){
        state.showModalDelete = true
    },
    closeModalDelete(state){
        state.showModalDelete = false
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
