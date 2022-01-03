
 const state  = {
  from          : '',
  to            : '',
  total_page    : '',
  current_page  : '',
  total         : '',
  per_page      : '',
}

 const mutations = {
  SET_PAGINATION(state,payload){
    state.from          = payload.from
    state.to            = payload.to
    state.total_page    = payload.last_page
    state.current_page  = payload.current_page
    state.total         = payload.total
    state.per_page      = payload.per_page
  },
}
 const actions = {
   setPagination({commit},payload) {
          commit('SET_PAGINATION',payload)
  }
}


export default {
  namespaced : true,
  state,
  mutations,
  actions
}
