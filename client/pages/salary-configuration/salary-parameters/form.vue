<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="payroll_parameter_id"
							rules="required">
							<div class="form-group">
								<label for="payroll_parameter_id"> Payroll Parameter</label>
                <select name="payroll_parameter_id" id="payroll_parameter_id" class="form-control">
                  <option value="">&mdash; select &mdash;</option>
                </select>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
            </div>
						<ValidationProvider
							name="value"
							rules="required">
							<div class="form-group">
								<label for="value">Formula</label>
                <textarea name="formula" id="formula" rows="10" class="form-control"></textarea>
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
      title: 'Payroll Parameter',
      parameters : {
        url : 'payroll-parameter-formula',
        form : {
          payroll_parameter_id : '',
          formula : '',
        }
      }
    };
  },

  computed :{
    ... mapState('moduleSalaryConfiguration',['error','result'])
  },

  methods: {
    ...mapActions('moduleSalaryConfiguration',['addData','updateData']),
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
