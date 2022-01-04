<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

        <modal-header-section/>

        <ValidationObserver
          v-slot="{invalid,validate}"
          ref="form-validate">
          <form @submit.prevent="validate().then(onSubmit(invalid))"
              autocomplete="off">

            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input id="fullname"
                      type="text"
                      class="form-control"
                      name="fullname"
                      v-model="parameters.form.fullname"/>               
                  </div>
                </div>

                <div class="col">
                  <ValidationProvider
                    name="username"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="uername">Username</label>
                      <input id="username"
                        type="text"
                        class="form-control"
                        name="username"
                        v-model="parameters.form.username"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>      
                    </div>
                  </ValidationProvider>
                </div>
              </div>

              <ValidationProvider
                name="email"
                rules="required">
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="email">Email</label>
                  <input id="email"
                    type="text"
                    class="form-control"
                    name="email"            
                    v-model="parameters.form.email"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                  <div class="invalid-feedback" v-if="errors[0]">
                    {{ errors[0] }}
                  </div>      
                </div>
              </ValidationProvider>
              
              <ValidationProvider
                name="password"
                :rules="isEditable ? 'min:8' : 'required|min:8'">
                <div class="form-group">
                  <label for="password">Password</label>
                  <input id="password"
                    type="text"
                    class="form-control"
                    name="password"            
                    v-model="parameters.form.password"/>
                </div>
              </ValidationProvider>

              <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control">
                  <option value="0">Super Admin</option>
                  <option value="1">Manager Nasional</option>            
                </select>                  
              </div>
            </div>

            <modal-footer-section 
              :self="this"/>
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
  props: ["self"],

  data() {
    return {
      isActive: "",
      isEditable  : false,
      title: 'User',
      roles : [
        {value : 0,title : "Super Admin"},
        {value : 1,title : "Manager Nasional"},
        {value : 2,title : "Mangaer Area"},
        {value : 3,title : "Kaper"},
        {value : 4,title : "Spv"},
        {value : 5,title : "Sales"}
      ],
      parameters : {
        url : 'user',
        form : {
          fullname : '',
          username : '',
          password : '',
          email    : '',
          role     : ''
        }
      }
    };
  },

  computed :{
     ...mapState('modulSetting',['error','result'])
  },

  methods: {
     ...mapActions('modulSetting',['addData','updateData']),

      onInitial() {
        let isActive = this.isActive;
        this.isActive = "";
      },

     async onSubmit(isInvalid){
      if(isInvalid || this.isLoadingForm) return false;      

      this.isLoadingForm = true;
      this.isEditable == true ? await this.updateData(this.parameters) : await this.addData(this.parameters) ;

      if (this.result == 'true') {
        this.$toaster.success('Data berhasil di '+ (this.isEditable == true ? 'Diedit': 'Tambah'));
        window.$("#modal-form").modal("hide");
      }else {
        this.$toaster.error(this.error);
      }

      this.isLoadingForm = false;
     },

    onClose() {
      this.onLoad();
    },
  },
};
</script>
