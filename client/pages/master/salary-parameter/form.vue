<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="parameter_name"
							rules="required">
							<div class="form-group">
								<label for="parameter_name">Parameter Name</label>
								<input id="parameter_name"
									type="text"
									class="form-control"
									name="parameter_name"
                  value="PR0001"
                  v-model="parameters.form.name"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
						<ValidationProvider
							name="parameter_type"
							rules="required">
							<div class="form-group">
								<label for="parameter_type">Type</label>
								<select name="parameter_type" id="" class="form-control" v-model="parameters.form.parameter_type">

                  <option v-for="type in self.salaryType" :key="type">{{ type }}</option>

                </select>
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
      title: 'Salary Parameters',
      parameters : {url : 'payroll-parameter',
                    form : {
                      name : '',
                      parameter_type : '',
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
<style scoped>
select.form-control:not([size]):not([multiple]) {
  height: calc(2.25rem + 6px);
}
</style>
