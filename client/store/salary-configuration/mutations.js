
export default {
  set_data : (state,payload) => {
    state.data = payload
  },
  
  set_error :(state,payload) => {
    state.error = payload
  },

  set_result :(state,payload) => {
    state.result = payload
  },

  set_lookup_overtime_parameter : (state,payload) => {
    state.lookup_overtime_parameter  = payload
  },
}
