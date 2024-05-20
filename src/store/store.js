import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      userState: [
        {
          userID: localStorage.getItem('userid'),
          userEmail: localStorage.getItem('email'),
          userLevel: localStorage.getItem('usertype'),
          address: localStorage.getItem('address'),
          name: localStorage.getItem('name'),
          token: localStorage.getItem('token')
        },
      ],
    };
  },
  mutations: {
    setUserID(state, newUserID) {
      state.userState[0].userID = newUserID;
    },
  },
});

export default store;
