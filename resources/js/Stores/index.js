import {createStore } from "vuex";
import owner from "@/Stores/Modals/owner.js";
import car from "@/Stores/Modals/car.js";

export default createStore({
    modules:{
        owner,
        car
    }
})
