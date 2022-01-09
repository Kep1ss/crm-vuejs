import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const defaultState = {
  data                     : [],
  code                     : '',
  error                    : '',
  result                   : '',
  lookup_province          : {
    data : []
  },
  lookup_city              : {
    data : []
  },
  lookup_district          : {
    data : []
  },
}

const state = defaultState;

export default {
  namespaced : true,
  state,
  actions,
  mutations,
  getters
}
