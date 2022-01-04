
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
  set_lookup_user : (state,payload) => {
    state.lookup_user  = payload
  }
}
