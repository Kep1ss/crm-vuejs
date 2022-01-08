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
                name="title"
                rules="required">
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="title">Judul</label>
                  <input id="title"
                    type="text"
                    class="form-control"
                    name="title"
                    v-model="parameters.form.title"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                  <div class="invalid-feedback" v-if="errors[0]">
                    {{ errors[0] }}
                  </div>      
                </div>
              </ValidationProvider>    

              <ValidationProvider
                name="link"
                rules="required">
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="link">Link</label>
                  <input id="link"
                    type="text"
                    class="form-control"
                    name="link"
                    v-model="parameters.form.link"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

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
      title: 'Download Catalog',      
      parameters : {
        url : 'download-catalog',
        form : {
          title : '',
          link : ''        
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
