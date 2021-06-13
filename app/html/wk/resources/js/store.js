import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        userName: "",
        imageUrl: "",
        messages: {
            type: null,
            message: []
        }
    },
    getters: {
        userName: (state) => {
            return state.userName;
        },
        imageUrl: (state) => {
            return state.imageUrl;
        },
        messageType: (state) => {
            return state.messages?.type;
        },
        messages: (state) => {
            return state.messages ? state.message : "予期せぬエラーが発生しました。";
        },
    },
    mutations: {
        setUserName: (state, name) => {
            state.userName = name;
        },
        clearMessages: (state) => {
            state.messages = { type: null, messages: [] };
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
        setMessages(state, type, message) {
            this.state.messges = {
                type: type ?? null,
                message: message ?? [],
            };
        },
    }
});

export default store;
