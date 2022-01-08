import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const defaultState = {
  data                     : [],
  code                     : '',
  error                    : '',
  result                   : '',
  // lookup_user              : [],
}

const state = defaultState;

export default {
  namespaced : true,
  state,
  actions,
  mutations,
  getters
}
