import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      userState: [
        //token dapat ito instead na static
        {
          userID: "111",
          userEmail: "a@a.com",
          userLevel: "Area Leader",
          address: "Loma De Gato",
        },
      ],
    };
  },
});

export default store;
