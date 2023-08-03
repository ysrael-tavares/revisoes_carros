import {ownerDefault} from "@/Utils/Examples.js";

const state = () => ({
    showModalInsertUpdate: false,
    data: {...ownerDefault}
})

const getters = {
    
}

const actions = {
    showModalOwner({state, commit}, ){
        commit('showModal')
    },
    closeModalOwner({state, commit}){
        commit('updateOwner', ownerDefault)
        commit('closeModal')
    },
    prepareEditOwner({state, commit}, owner) {
        commit('updateOwner', owner)
        commit('showModal')
    },
}

const mutations = {
    showModal(state){
        state.showModalInsertUpdate = true
    },
    closeModal(state){
        state.showModalInsertUpdate = false
    },
    updateOwner(state, owner){
        state.data = {...owner}
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
