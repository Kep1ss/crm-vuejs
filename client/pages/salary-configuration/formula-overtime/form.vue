<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="overtime_category_id"
							rules="required">
							<div class="form-group">
								<label for="overtime_category_id">Kategori Lembur</label>
                <select name="overtime_category_id" id="overtime_category_id" class="form-control">
                  <option value="">&mdash; select &mdash;</option>
                </select>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
						<ValidationProvider
							name="index_formula_id"
							rules="required">
							<div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="overtime_category_id">Parameter Lembur</label>
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="customCheck1">  
                      <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
                    </div>
                  </div>
                </div>
              </div>
						</ValidationProvider>
						<ValidationProvider
							name="value"
							rules="required">
							<div class="form-group">
								<label for="value">Formula</label>
                <textarea name="formula" id="formula" rows="10" class="form-control" readonly></textarea>
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
      title: 'Formula Overtime',
      parameters : {
        url : 'overtime-formula',
        form : {
          overtime_category_id : '',
          index_formula_id : '',
          formula : '',
        }
      }
    };
  },

  computed :{
    ... mapState('moduleSalaryConfiguration',['error','result','lookup_overtime_parameter'],{
      lookup_overtime_parameter : state => state.lookup_overtime_parameter  ,
    })
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
