import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      userState: [
        {
          userID: "111",
          userEmail: "a@a.com",
          userLevel: "Admin",
          address: "Loma De Gato",
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
