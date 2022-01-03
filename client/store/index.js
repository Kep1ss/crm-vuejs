import Vuex from 'vuex';
import ModulMaster from "./module-master";
import ModuleSalaryConfiguration from "./salary-configuration";
import Pagination from './pagination';
import Print from './print';

const createStore = () => {
  return new Vuex.Store({
    namespaced : true,
    modules : {
      modulMaster : ModulMaster,
      moduleSalaryConfiguration : ModuleSalaryConfiguration,
      pagination  : Pagination,
      print       : Print,
    }
  })
}

export default createStore
