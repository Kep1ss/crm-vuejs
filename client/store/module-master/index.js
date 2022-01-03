import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const defaultState = {

  data                     : [],
  lookup_division          : [],
  lookup_position          : [],
  lookup_cites             : [],
  lookup_overtime_category : [],
  code            : '',
  error           : '',
  result          : '',

}
const state = defaultState;
export default {
  namespaced : true,
  state,
  actions,
  mutations,
  getters
}
