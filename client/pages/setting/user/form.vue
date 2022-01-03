<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="division_code"
							rules="required">
							<div class="form-group">
								<label for="division_code">Division code</label>
								<input id="division_code"
									type="text"
									class="form-control"
									name="division_code"
                  v-model="parameters.form.code"
                  value="DV0001"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
						<ValidationProvider
							name="division_name"
							rules="required">
							<div class="form-group">
								<label for="division_name">Division name</label>
								<input id="division_name"
									type="text"
									class="form-control"
									name="division_name"
                  value="Some division name"
                  v-model="parameters.form.name"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
					</div>

          <modal-footer-section :self="this"></modal-footer-section>
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
