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
                name="content"
                rules="required">
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="content">Isi</label>
                  <textarea class="form-control"                
                    name="content"
                    v-model="parameters.form.content"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"></textarea>

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
  middleware : ["isNotAccessable"],

  props: ["self"],

  data() {
    return {
      isEditable  : false,
      isLoadingForm : false,
      title: 'Pengumuman',      
      parameters : {
        url : 'announcement',
        form : {
          content : '',        
        }
      }
    };
  },

  computed :{
     ...mapState('modulSetting',['error','result'])
  },

  methods: {
     ...mapActions('modulSetting',['addData','updateData']),

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
     }
  },
};
</script>
