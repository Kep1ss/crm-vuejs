

const actions = {
  printFile({},payload) {
    var token = this.$auth.ctx.$cookiz.get(('auth._token.local')).replace('Bearer ','');
    window.open(process.env.API_URL+'/'+payload+'/print?token='+token+'&all=true','_blank');
  },
  exportFile({},payload){
    var token = this.$auth.ctx.$cookiz.get(('auth._token.local')).replace('Bearer ','');
    console.log(process.env.API_URL+'/'+payload.url+'/export/'+payload.type+'?token='+token+'&all='+payload.params.all);
    window.open(process.env.API_URL+'/'+payload.url+'/export/'+payload.type+'?token='+token+'&all='+payload.params.all,'_blank');
  }
}

export default {
  namespaced : true,
  actions,
}
