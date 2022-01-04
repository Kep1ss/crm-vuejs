<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section/>

					<div class="modal-body">
            <div class="form-group">
              <label for="fullname">Nama Lengkap</label>
              <input id="fullname"
                type="text"
                class="form-control"
                name="fullname"
                v-model="parameters.form.fullname"/>
                <div class="invalid-feedback"></div>
            </div>

						<ValidationProvider
							name="username"
							rules="required">
							<div class="form-group">
								<label for="uername">Username</label>
								<input id="username"
									type="text"
									class="form-control"
									name="username"
                  v-model="parameters.form.username"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>

						<ValidationProvider
							name="email"
							rules="required">
							<div class="form-group">
								<label for="email">Email</label>
								<input id="email"
									type="text"
									class="form-control"
									name="email"            
                  v-model="parameters.form.email"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
            
            <ValidationProvider
							name="password"
							rules="required">
							<div class="form-group">
								<label for="password">Password</label>
								<input id="password"
									type="text"
									class="form-control"
									name="password"            
                  v-model="parameters.form.password"/>
								<div class="invalid-feedback"></div>
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
        </div>
      </div>
    </div>
  </portal>
</template>

<script>

import { mapActions,mapState} from 'vuex'
export default {
  props: ["self"],
  data() {
    return {
      isActive: "",
      isEditable  : false,
      title: 'Division',
      parameters : {url : 'division',
                    form : {
                      code : '',
                      name : '',
                    }
      }
    };
  },
  computed :{
    ... mapState('modulMaster',['error','result'])
  },

  methods: {
    ...mapActions('modulMaster',['addData','updateData']),
    onInitial() {
      let isActive = this.isActive;
      this.isActive = "";
    },

     async onSubmit(isInvalid){
            if(isInvalid || this.isLoadingForm){
              return false;
            }

            this.isLoadingForm = true;
            this.isEditable == true ?
              await this.updateData(this.parameters) : await this.addData(this.parameters) ;

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
