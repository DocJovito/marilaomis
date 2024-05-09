import { createStore } from 'vuex';

const store = createStore({
  state() {
    return {
      isAuthenticated: false // Initialize as false, assuming user is not authenticated initially
    };
  },
  mutations: {
    setIsAuthenticated(state, isAuthenticated) {
      state.isAuthenticated = isAuthenticated;
    }
  },
  actions: {
    // You may have other actions for login, logout, etc.
  }
});

export default store;
