
export default {
  set_data : (state,payload) => {
    state.data = payload
  },
  set_code : (state,payload) => {
    state.code = payload
  },
  set_error :(state,payload) => {
    state.error = payload
  },
  set_result :(state,payload) => {
    state.result = payload
  },
  set_lookup_division : (state,payload) => {
    state.lookup_division  = payload
  },
  set_lookup_position : (state,payload) => {
    state.lookup_position  = payload
  },
  set_lookup_cites : (state,payload) => {
    state.lookup_cites  = payload
  }

}
