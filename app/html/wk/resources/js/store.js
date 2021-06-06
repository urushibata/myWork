import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        userName: "",
        imageUrl: ""
    },
    getters: {
        userName: (state) => {
            return state.userName;
        },
        imageUrl: (state) => {
            return state.imageUrl;
        },
    },
    mutations: {
        setUserName: (state, name) => {
            return state.userName = name;
        },
    },
    actions: {
        init() {
            axios.post("/api/vuexInit").then(response => {
                this.state.imageUrl = response.data.img_url;
                this.state.userName = response.data.user_name;
            }).catch(error => {
                console.error(error);
            });
        },
    }
});

export default store;
