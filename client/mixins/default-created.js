export default {    
  created(){
    /* FIRST LOAD */
    this.onLoad();

    /* INITIAL RESET SEARCH AND FORM */
    this.search_reset = Object.assign({},this.search);
    this.form_reset = Object.assign({},this.form);
  },
}