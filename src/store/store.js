import { createStore } from 'vuex';

const store = createStore({
  state: {
    user: {
      id: null,
      email: null,
      userType: null,
      name: null,
      address: null,
      token: null
    },
    isAuthenticated: false
  },
  mutations: {
    setUser(state, userData) {
      state.user = userData;
      state.isAuthenticated = true;
    },
    clearUser(state) {
      state.user = {
        id: null,
        email: null,
        userType: null,
        name: null,
        address: null,
        token: null
      };
      state.isAuthenticated = false;
    },
    setUserType(state, userType) {
      state.user.userType = userType;
    }
  },
  actions: {
    logIn({ commit }, userData) {
      commit('setUser', userData);
    },
    logOut({ commit }) {
      commit('clearUser');
    },
    setUserType({ commit }, userType) {
      commit('setUserType', userType);
    }
  },
  getters: {
    userType: state => state.user.userType
  }
});

export default store;
