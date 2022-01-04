import Vuex from 'vuex';

import Pagination from './pagination';
import Print from './print';
import Setting from './setting';

import ModulSetting from "./module-setting";

const createStore = () => {
  return new Vuex.Store({
    namespaced : true,
    modules : {
      modulSetting : ModulSetting,

      pagination  : Pagination,
      print       : Print,
      setting     : Setting
    }
  })
}

export default createStore
