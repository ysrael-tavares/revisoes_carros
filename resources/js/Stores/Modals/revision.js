import {defaultCar, defaultRevision} from "@/Utils/Examples.js";

const state = () => ({
    showModalInsertUpdate: false,
    car: {...defaultCar},
    data: {...defaultRevision},
    list: []
})

const getters = {

}

const actions = {
    showModalRevision({state, commit}, revision){
        commit('showModal')
        commit('updateRevision', revision)
    },
    closeModalRevision({state, commit}){
        commit('updateRevision', defaultRevision)
        commit('closeModal')
    },
    viewRevisions({state, commit}, car){
        commit('updateCar', car)
        commit('showModal')
    },
    prepareEditRevision({state, commit}, revision) {
        commit('updateRevision', revision)
        commit('showModal')
    },
    updateRevision({state, commit}, revision) {
        commit('updateRevision', revision)
    },
}

const mutations = {
    showModal(state){
        state.showModalInsertUpdate = true
    },
    closeModal(state){
        state.showModalInsertUpdate = false
    },
    updateRevision(state, revision){
        state.data = {...revision}
    },
    updateCar(state, car){
        state.car = {...car}
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
