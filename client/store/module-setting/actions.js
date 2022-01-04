export default {
   async getData({commit,dispatch},payload) {
       return  await this.$axios.get('/'+payload.url,{params :payload.params})
          .then ((response) => {
            commit('set_data',response.data.data)
            commit('set_result','true');
            dispatch("pagination/setPagination",response.data, {root:true})
          }).catch((error) => {
            commit('set_error',error.response.data.message)
          });
    },

    addData({commit,dispatch},payload){
       return this.$axios.post('/'+payload.url,payload.form)
        .then((response) => {
          dispatch('getData',payload);
          commit('set_result','true');
        }).catch((error) =>{
          commit('set_error',error.response.data.message)
        })
    },

    updateData({commit,dispatch},payload){
      return this.$axios.put('/'+payload.url+'/'+payload.form.id,payload.form)
        .then((response) => {
          dispatch('getData',payload);
          commit('set_result','true');
        }).catch((error) =>{
          commit('set_error',error.response.data.message)
        })
    },

    deleteData({commit,dispatch},payload){
      return this.$axios.delete('/'+payload.url+'/'+payload.id)
        .then((response) => {
          dispatch('getData',payload);
          commit('set_result','true');
        }).catch((error) =>{
          commit('set_error',error.response.data.message)
        })
    },

    genCode({commit},payload){
      return this.$axios.get('/'+payload.url+'/getcode')
        .then((response) => {
          commit('set_code',response.data.code);
        }).catch((error) =>{
          commit('set_error',error.response)
        })
    },

    lookUp({commit},payload){
      let url = (payload.url == 'employe/getcites' ? payload.url : payload.url+'?all=true');
      return this.$axios.get('/'+url)
      .then((response) => {
        switch(payload.url) {
          case 'division':
            commit('set_lookup_division',response.data)
            break;
          case 'position':
            commit('set_lookup_position',response.data)
            break;
          case 'employe/getcites':
            commit('set_lookup_cites',response.data)
            break;
        }

      }).catch((error) => {
        commit('set_error', error.response.data.message)
      })
    },

}
