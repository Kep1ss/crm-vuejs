export default{
  getData(state){
    return state.data
  },
  getLookUpProvince(state){
    return state.lookup_province;
  },
  getLookUpCity(state){
    return state.lookup_city;
  },
  getLookUpDistrict(state){
    return state.lookup_district;
  }
}
