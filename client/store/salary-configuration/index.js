import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const defaultState = {
  data                       : [],
  lookup_overtime_parameter  : [],
  error  : '',
  result : '',
}

const state = defaultState;
export default {
  namespaced : true,
  state,
  actions,
  mutations,
  getters
}
