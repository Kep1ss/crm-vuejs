import Vuex from 'vuex';

import Pagination from './pagination';
import Print from './print';
import Setting from './setting';

import ModulSetting from "./module-setting";
import ModulMaster from "./module-master";

const createStore = () => {
  return new Vuex.Store({
    namespaced : true,
    modules : {
      modulSetting : ModulSetting,
      modulMaster : ModulMaster,

      pagination  : Pagination,
      print       : Print,
      setting     : Setting
    }
  })
}

export default createStore
