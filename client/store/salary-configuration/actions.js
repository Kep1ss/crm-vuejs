

export default {
    getData({commit,dispatch},payload) {
       return  this.$axios.get('/'+payload.url,{params :payload.params})
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

    lookUp({commit},payload){
      return this.$axios.get(payload+'/formula-index')
      .then((response) => {
        switch(payload.url) {
          case 'overtime_parameter':
            commit('set_overtime_parameter',response.data)
            break;
        }

      }).catch((error) => {
        commit('set_error', error.response.data.message)
      })
    },
}
