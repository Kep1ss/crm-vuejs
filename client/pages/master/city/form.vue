<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

        <modal-header-section
          :self="this"/>

        <ValidationObserver
          v-slot="{invalid,validate}"
          ref="form-validate">
            <form @submit.prevent="validate().then(onSubmit(invalid))"
              autocomplete="off">

            <div class="modal-body">            
              <ValidationProvider
                name="name"
                rules="required">
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="name">Nama</label>
                  <input id="name"
                    type="text"
                    class="form-control"
                    name="name"
                    v-model="parameters.form.name"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                  <div class="invalid-feedback" v-if="errors[0]">
                    {{ errors[0] }}
                  </div>      
                </div>
              </ValidationProvider>                                            

              <div class="form-group">
                <label for="is_city">Status</label> 
                <select name="is_city"
                  id="is_city"
                  class="form-control"
                  v-model="parameters.form.is_city">
                  <option value="0">Kabupaten</option>
                  <option value="1">Kota</option>
                </select>
              </div>

              <!--
              <ValidationProvider 
                name="province_id"
                rules="required">                        
                <div class="form-group" slot-scope="{errors,valid}">             
                  <label for="province_id">Province</label>                        
                  <input type="hidden"
                    id="province_id" 
                    class="form-control" 
                    name="province_id"                      
                    v-model="parameters.form.province_id"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/> 
                                    
                    <v-select                           
                      :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                      label="name"   
                      :loading="isLoadingGetProvince"
                      :options="lookup_province.data"
                      :filterable="false"
                      @search="onGetProvince"
                      v-model="parameters.form.province_id">              
                      <li slot-scope="{search}" slot="list-footer"
                        class="d-flex justify-content-between"
                        v-if="lookup_province.data.length || search">
                        <a v-if="lookup_province.current_page > 1" 
                          @click="onGetProvince(search,false)"
                          class="flex-fill bg-primary text-white text-center"
                          href="#">Sebelumnya</a>
                        <a v-if="lookup_province.last_page > lookup_province.current_page" 
                          @click="onGetProvince(search,true)"
                          class="flex-fill bg-primary text-white text-center"
                          href="#">Selanjutnya</a>
                      </li> 
                    </v-select>

                    <div class="invalid-feedback" v-if="errors[0]">
                      {{ errors[0] }}
                    </div>                
                </div>                           
              </ValidationProvider> 
              -->
            </div>

            <modal-footer-section     
              :isLoadingForm="isLoadingForm"/>
          </form>
        </ValidationObserver>
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {  
  mounted(){
    this.lookUp({    
      url : "province",      
      lookup  : 'province',
      query : "?per_page=20"
    })
  },

  middleware : ["isNotAccessable"],

  props: ["self"],

  data() {
    return {
      isEditable  : false,
      isLoadingForm : false,
      isStopSearchProvince : false,
      isLoadingGetProvince :  false,
      province_search : '',
      title: 'Kota',      
      parameters : {
        url : 'city',
        form : {
         name : '',      
         province : '',
         province_id : '',
         is_city : 0
        } 
      }
    };
  },

  computed :{
     ...mapState('modulMaster',['error','result','lookup_province']),     
  },

  methods: {    
     ...mapActions('modulMaster',['addData','updateData','lookUp']),

     async onSubmit(isInvalid){           
      if(isInvalid || this.isLoadingForm) return;            
      
      let parameters  = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          province_id : typeof this.parameters.form.province_id  === 'object' 
            ? this.parameters.form.province_id.id  
            : this.parameters.form.province_id
        }
      }

      this.isLoadingForm = true;

      if(this.isEditable){
        await this.updateData(parameters)
      }else{ 
        await this.addData(parameters)
      }

      if (this.result == true) {      
        this.self.onLoad(this.self.parameters.params.page);  
        this.$toaster.success('Data berhasil di '+ (this.isEditable == true ? 'Diedit': 'Tambah'));
        window.$("#modal-form").modal("hide");
      }else {
        this.$globalErrorToaster(this.$toaster,this.error);      
      }

      this.isLoadingForm = false;
     },

     onGetProvince(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchProvince);
      
      this.isStopSearchProvince = setTimeout(() => {
        this.province_search = search;

        if(typeof isNext !== "function"){
          this.lookup_province.current_page = isNext 
            ? this.lookup_province.current_page + 1 
            : this.lookup_province.current_page - 1;        
        }else{
          this.lookup_province.current_page = 1;
        }

        this.onSearchProvince();
      },600);        
    },

    async onSearchProvince(){
      if(!this.isLoadingGetProvince){
        this.isLoadingGetProvince = true;
                
        await this.lookUp({    
          url : "province",      
          lookup  : 'province',
          query : "?search="+this.province_search+"&page="+this.lookup_province.current_page+"&per_page=20"
        })

        this.isLoadingGetProvince = false;      
      }
    }
  },
};
</script>
