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

              <ValidationProvider 
                name="city_id"
                rules="required">                        
                <div class="form-group" slot-scope="{errors,valid}">             
                  <label for="city_id">Kota</label>                        
                  <input type="hidden"
                    id="city_id" 
                    class="form-control" 
                    name="city_id"                      
                    v-model="parameters.form.city_id"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/> 
                                    
                    <v-select                           
                      :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                      label="name"   
                      :loading="isLoadingGetCity"
                      :options="lookup_city.data"
                      :filterable="false"
                      @search="onGetCity"
                      v-model="parameters.form.city_id">              
                      <li slot-scope="{search}" slot="list-footer"
                        class="d-flex justify-content-between"
                        v-if="lookup_city.data.length || search">
                        <a v-if="lookup_city.current_page > 1" 
                          @click="onGetCity(search,false)"
                          class="flex-fill bg-primary text-white text-center"
                          href="#">Sebelumnya</a>
                        <a v-if="lookup_city.last_page > lookup_city.current_page" 
                          @click="onGetCity(search,true)"
                          class="flex-fill bg-primary text-white text-center"
                          href="#">Selanjutnya</a>
                      </li> 
                    </v-select>

                    <div class="invalid-feedback" v-if="errors[0]">
                      {{ errors[0] }}
                    </div>                
                </div>                           
              </ValidationProvider> 
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
      url : "district/city",      
      lookup  : 'city',
      query : "?per_page=50"
    })
  },

  middleware : ["isNotAccessable"],

  props: ["self"],

  data() {
    return {
      isEditable  : false,
      isLoadingForm : false,
      isStopSearchCity : false,
      isLoadingGetCity :  false,
      city_search : '',
      title: 'Kecamatan',      
      parameters : {
        url : 'district',
        form : {
         name : '',      
         city : '',
         city_id : '',      
        }
      }
    };
  },

  computed :{
     ...mapState('modulMaster',['error','result','lookup_city']),     
  },

  methods: {    
     ...mapActions('modulMaster',['addData','updateData','lookUp']),

     async onSubmit(isInvalid){           
      if(isInvalid || this.isLoadingForm) return;            
      
      let parameters  = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          city_id : typeof this.parameters.form.city_id  === 'object' 
            ? this.parameters.form.city_id.id  
            : this.parameters.form.city_id
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

     onGetCity(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchCity);
      
      this.isStopSearchCity = setTimeout(() => {
        this.city_search = search;

        if(typeof isNext !== "function"){
          this.lookup_city.current_page = isNext 
            ? this.lookup_city.current_page + 1 
            : this.lookup_city.current_page - 1;        
        }else{
          this.lookup_city.current_page = 1;
        }

        this.onSearchCity();
      },600);        
    },

    async onSearchCity(){
      if(!this.isLoadingGetCity){
        this.isLoadingGetCity = true;
                
        await this.lookUp({    
          url : "district/city",      
          lookup  : 'city',
          query : "?search="+this.city_search+"&page="+this.lookup_city.current_page+"&per_page=50"
        })

        this.isLoadingGetCity = false;      
      }
    }
  },
};
</script>
