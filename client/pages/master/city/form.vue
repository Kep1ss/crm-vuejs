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

              <div class="form-group">
                <label for="province_id">Province</label>
                <select name="province_id"
                  id="province_id"
                  class="form-control"
                  v-model="parameters.form.province_id">
                  <option v-for="item in lookup_province"
                    :key="item.id">
                    {{item.name}}
                  </option>
                </select>
              </div>
            </div>

            <modal-footer-section     
              :isLoadingForm="isLoadingForm"/>
          </form>
        </ValidationObserver>
          <button @click="klikChek">asd</button>
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {  
  middleware : ["isNotAccessable"],

  props: ["self"],

  mounted(){
    this.lookUp({    
      url : "city/province",      
    })
  },

  data() {
    return {
      isEditable  : false,
      isLoadingForm : false,
      title: 'Provinsi',      
      parameters : {
        url : 'province',
        form : {
         name : '',
         province : '',
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
      
      this.isLoadingForm = true;

      if(this.isEditable){
        await this.updateData(this.parameters)
      }else{ 
        await this.addData(this.parameters)
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

     klikChek(){
       console.log(this.lookup_province);
     }
  },
};
</script>
