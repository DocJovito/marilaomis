import { createStore } from "vuex";

// Create a new Vuex store instance
const store = createStore({
  state() {
    return {
      userState: [
        //token dapat ito instead na static
        {
          userID: "111",
          userEmail: "a@a.com",
          userLevel: "Admin",
          address: "All",
        },
      ],
    };
  },
});

export default store; // Export the store as default
