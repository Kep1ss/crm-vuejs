export default {
  getData({commit,dispatch},payload) {
    return this.$axios.get('/'+payload.url,{
        params : payload.params
      })
      .then (response => {
        commit('set_data',response.data.data)
        commit('set_result',true);
        dispatch("pagination/setPagination",response.data, {
          root : true
        })
      }).catch(error => {
        commit('set_result',false)
        commit('set_error',error)
      });
  },

  addData({commit,dispatch},payload){
    return this.$axios.post('/'+payload.url,payload.form)
      .then(response => {
        commit('set_result',true);
      }).catch(error =>{
        commit('set_result',false)
        commit('set_error',error)
      })
  },

  updateData({commit,dispatch},payload){
    return this.$axios.put('/'+payload.url+'/'+payload.form.id,payload.form)
      .then(response => {
        commit('set_result',true);
      }).catch(error =>{
        commit('set_result',false)
        commit('set_error',error)
      })
  },

  deleteData({commit,dispatch},payload){
    return this.$axios.delete('/'+payload.url+'/'+payload.id)
      .then(response => {
        commit('set_result',true);
      }).catch(error =>{
        commit('set_result',false)
        commit('set_error',error)
      })
  },

  deleteAllData({commit,dispatch},payload){
    return this.$axios.delete("/"+payload.url+"/destroy-all",{
        data : {
          checkboxs : payload.checkboxs,
        }
      }).then(response => {
        commit('set_result',true)
      }).catch(error => {
        commit('set_result',false)
        commit('set_error',error)
      })
  },

  restoreData({commit,dispatch},payload){
    return this.$axios.post("/"+payload.url+"/restore/"+payload.id)
    .then(response => {
      commit('set_result',true)
    }).catch(error => {
      commit('set_result',false)
      commit('set_error',error)
    })
  },

  restoreAllData({commit,dispatch},payload){
    return this.$axios.post("/"+payload.url+"/restore-all",{
      checkboxs : payload.checkboxs
    })
    .then(response => {
      commit('set_result',true)
    }).catch(error => {
      commit('set_result',false)
      commit('set_error',error)
    })
  },

  genCode({commit},payload){
    return this.$axios.get('/'+payload.url+'/getcode')
      .then(response => {
        commit('set_code',response.data.code);
      }).catch(error =>{
        commit('set_error',error)
      })
  },

  lookUp({commit},payload){
    let url = payload.url + (payload.query || "");
    return this.$axios.get('/'+url)
    .then(response => {
      commit('set_result',true)
      switch(payload.lookup) {
        case 'province':
          commit('set_lookup_province',response.data)
          break;
        case 'city':
          commit('set_lookup_city',response.data)
          break;
        case 'district':
          commit('set_lookup_district',response.data)
          break;
      }
    }).catch(error => {
      commit('set_result',false)
      commit('set_error', error.response)
    })
  },
}
