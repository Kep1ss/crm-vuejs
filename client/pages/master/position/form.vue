<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="position_code"
							rules="required">
							<div class="form-group">
								<label for="position_code">Position code</label>
								<input id="position_code"
									type="text"
									class="form-control"
									name="position_code"
                  value="PS0001" v-model="parameters.form.code"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
						<ValidationProvider
							name="position_name"
							rules="required">
							<div class="form-group">
								<label for="dpositionname">Position name</label>
								<input id="position_name"
									type="text"
									class="form-control"
									name="position_name"
                  value="Some position name" v-model="parameters.form.name"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
					</div>

          <modal-footer-section  :self="this"></modal-footer-section>
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
      title: 'Position',
      parameters : {url : 'position',
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
              this.$toaster.success(' Data berhasil di '+ (this.isEditable == true ? 'Diedit': 'Tambah'));
              window.$("#modal-form").modal("hide");
            }else {
              this.$toaster.error(this.error);
            }
            this.isLoadingForm = false;

      },

    onClose() {
      this.self.onLoad();
    },
  },
};
</script>
