<template>
  <portal to="modal">
    <div class="modal fade" aria-hidden="true" id="modal-form">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <modal-header-section></modal-header-section>

					<div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <ValidationProvider
                  name="nik"
                  rules="required">
                  <div class="form-group">
                    <label for="nik">NIK</label>
                    <input id="nik"
                      type="text"
                      class="form-control bg-light"
                      name="nik"
                      value="PS0001"
                      v-model="parameters.form.code"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-4">
                <ValidationProvider
                  name="number_id"
                  rules="required">
                  <div class="form-group">
                    <label for="number_id">Citizen ID (KTP)</label>
                    <input id="number_id"
                      type="text"
                      class="form-control bg-light"
                      name="number_id"
                      value="EMP-1"
                      v-model="parameters.form.citizen_id"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-4">
                <ValidationProvider
                  name="join_date"
                  rules="required">
                  <div class="form-group">
                    <label for="join_date">Joined at</label>
                    <input id="join_date"
                      type="date"
                      class="form-control"
                      name="join_date"
                      placeholder=""
                      min="1990-01-01"
                      v-model="parameters.form.join_date"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="name"
                  rules="required">
                  <div class="form-group">
                    <label for="name">Fullname</label>
                    <input id="name"
                      type="text"
                      class="form-control"
                      name="name"
                      value="Abdullah"
                      v-model="parameters.form.name"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-6">
                <ValidationProvider
                  name="birth_day"
                  rules="required">
                  <div class="form-group">
                    <label for="birth_day">Date of birth</label>
                    <input id="birth_day"
                      type="date"
                      class="form-control"
                      name="birth_day"
                      placeholder=""
                      min="1980-01-01"
                      v-model="parameters.form.birth_day"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="gender"
                  rules="required">
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control" v-model="parameters.form.gender">
                      <option value="" disabled selected>&mdash; Pilih &mdash; </option>
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-6">
                <ValidationProvider
                  name="academic"
                  rules="required">
                  <div class="form-group">
                    <label for="graduation">Graduation</label>
                    <select name="graduation" id="graduation" class="form-control" v-model="parameters.form.graduate">
                      <option value="" disabled selected>&mdash; Pilih &mdash; </option>
                      <option value="SD">SD</option>
                      <option value="SMP">SMP</option>
                      <option value="SMA">SMA</option>
                      <option value="D1">D1</option>
                      <option value="D2">D2</option>
                      <option value="D3">D3</option>
                      <option value="D4">D4</option>
                      <option value="S1">S1</option>
                      <option value="S2">S2</option>
                      <option value="S3">S3</option>
                    </select>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="address"
                  rules="required">
                  <div class="form-group">
                    <label for="address">Address</label>
                    <input
                      name="city"
                      id="city"
                      class="form-control"
                      value="Perumahan Griya Permai, No. 22, Gayam"
                      v-model="parameters.form.address">
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-6">
                <ValidationProvider
                  name="city"
                  rules="required">
                  <div class="form-group">
                    <label for="city">City</label>
                    <input
                      name="city"
                      id="city"
                      class="form-control"
                      value="Sukoharjo"
                      v-model="parameters.form.city">
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>

            </div>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="phone"
                  rules="required">
                  <div class="form-group">
                    <label for="phone">Phone</label>
                    <input
                      name="phone"
                      id="phone"
                      class="form-control"
                      value="+6281234567890"
                      v-model="parameters.form.phone">
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-6">
                <ValidationProvider
                  name="npwp"
                  rules="required">
                  <div class="form-group">
                    <label for="npwp">Taxpayer Number (NPWP)</label>
                    <input
                      name="npwp"
                      id="npwp"
                      class="form-control"
                      value="58.756.790.8-134.000"
                      v-model="parameters.form.npwp">
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="division_id"
                  rules="required">
                  <div class="form-group">
                    <label for="division_id">Division</label>
                    <select name="division_id" id="division_id"
                    class="form-control select2"
                    v-model="parameters.form.division_id">
                      <option value="" disabled selected>&mdash; Pilih &mdash; </option>
                      <option  v-for="(item,i) in lookup_division" :key="i" :value="item.id"> {{item.name}}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-6">
                <ValidationProvider
                  name="position_id"
                  rules="required">
                  <div class="form-group">
                    <label for="position_id">Position</label>
                    <select name="position_id" id="position_id"
                    class="form-control"
                    v-model="parameters.form.position_id">
                      <option value="" disabled selected>&mdash; Pilih &mdash; </option>
                      <option  v-for="(item,i) in lookup_position" :key="i" :value="item.id">{{item.name}}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <ValidationProvider
                  name="re_signed"
                  rules="required">
                  <div class="form-group">
                    <label for="re_signed">Resigned at *</label>
                    <input id="re_signed"
                      type="date"
                      class="form-control"
                      name="re_signed"
                      v-model="parameters.form.resigned"/>
                    <small>Filled when the employee has resigned</small>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>

              <div class="col-md-6">
                <ValidationProvider
                  name="salary"
                  rules="required">
                  <div class="form-group">
                    <label for="salary">Salary</label>
                    <input id="salary"
                      class="form-control"
                      name="salary"
                      v-model="parameters.form.salary"/>
                    <div class="invalid-feedback"></div>
                  </div>
                </ValidationProvider>
              </div>
            </div>
					</div>

          <modal-footer-section   :self="this"></modal-footer-section>
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import {mapActions,mapState} from 'vuex';

export default {
  props: ["self"],


  data() {
    return {
      isActive: "",
      title: 'Position',
      divisions   : [],
      posisions   : [],
      parameters  : {
        url : 'employe',
        form :{
          code        : '',
          name        : '',
          citizen_id  : '',
          gender      : '',
          address     : '',
          city        : '',
          birth_day   : '',
          graduate    : '',
          phone       : '',
          position_id : '',
          join_date   : '',
          resigned    : '',
          npwp        : '',
          salary      : '',
        }
      }
    };
  },
  computed :{
    ...mapState('modulMaster',['lookup_division','lookup_position','error','result'],{
      lookup_division : state => state.lookup_division,
      lookup_position : state => state.lookup_position,
    })
  },
  methods: {

    ... mapActions('modulMaster',['addData','updateData']),
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
            //  console.log('errrorrr')
              this.$toaster.success(' Data berhasil di '+ (this.isEditable == true ? 'Diedit': 'Tambah'));
              window.$("#modal-form").modal("hide");
            }else {

              this.$toaster.error(this.error);
            }
            this.isLoadingForm = false;

      },

    onClose() {
    //  this.self.onLoad();
    },
  },
};
</script>
<style scoped>
select.form-control:not([size]):not([multiple]) {
  height: calc(2.25rem + 6px);
}
</style>
