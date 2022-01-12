const state = () => ({
    settings    : [],
    roles       : {
      superadmin        : 0,
      manager_nasional  : 1,
      manager_area      : 2,
      kaper             : 3,
      spv               : 4,
      sales             : 5,
      kotele            : 6,
      tele_marketing    : 7,   
    },
    getRoles(targets,roles){      
      let new_roles = {};

      for (const item in roles) {
        if(targets.includes(roles[item])){
          new_roles[item] = roles[item]
        }      
      }      
      
      return new_roles;
    }
})

const mutations = {
     SET_SETTINGS: (state,settings) => {
        state.settings = settings;
     }
}

const actions = {
    async nuxtClientInit({commit},{app}) {
      try{   
        let response = await app.$axios.get("/get-setting");    
        commit("SET_SETTINGS",response.data)
      }catch(err){
        console.log(err);
        commit("SET_SETTINGS",[]);
      }
    }
}

export default {
  namespaced : true,
  state,
  mutations,
  actions,
}