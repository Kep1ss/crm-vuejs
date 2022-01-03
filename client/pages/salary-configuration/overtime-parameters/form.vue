<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
						<ValidationProvider
							name="name"
							rules="required">
							<div class="form-group">
								<label for="name">Formula index name</label>
								<input id="name"
									type="text"
									class="form-control"
									name="name"
                  placeholder="Name"
                  v-model="parameters.form.name"/>
								<div class="invalid-feedback"></div>
							</div>
						</ValidationProvider>
						<ValidationProvider
							name="value"
							rules="required">
							<div class="form-group">
								<label for="value">Value</label>
								<input id="value"
									type="number"
									class="form-control"
									name="value"
                  placeholder="1.00"
                  v-model="parameters.form.value"/>
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
      title: 'Index Formula',
      parameters : {
        url : 'index-formula',
        form : {
          name  : '',
          value : '',
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
