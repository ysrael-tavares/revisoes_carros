import {defaultCar, ownerDefault} from "@/Utils/Examples.js";

const state = () => ({
    showModalInsertUpdate: false,
    showFormInsertUpdate: false,
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
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
