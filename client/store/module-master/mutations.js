
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
  set_lookup_province : (state,payload) => {
    state.lookup_province  = payload
  },
  set_lookup_city : (state,payload) => {
    state.lookup_city = payload
  },
  set_lookup_district : (state,payload) => {
    state.lookup_district = payload
  }
}
