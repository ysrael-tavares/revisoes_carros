import {createStore } from "vuex";
import owner from "@/Stores/Modals/owner.js";
import car from "@/Stores/Modals/car.js";
import revision from "@/Stores/Modals/revision.js";

export default createStore({
    modules:{
        owner,
        car,
        revision
    }
})
