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
            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label for="description">Keterangan</label>
                    <input id="description"
                      type="text"
                      class="form-control"
                      name="description"
                      v-model="parameters.form.description"/>
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col">
                  <div class="form-group">
                    <label for="target_copies">Target Eksemplar</label>
                    <input id="target_copies"
                      type="text"
                      class="form-control"
                      name="target_copies"
                      v-model="parameters.form.target_copies"/>
                  </div>
                </div>
            </div>

              <ValidationProvider
                name="email"
                rules="required|email">
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
                <div class="form-group" slot-scope="{errors,valid}">
                  <label for="password">Password</label>
                  <input id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    v-model="parameters.form.password"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                  <div class="invalid-feedback" v-if="errors[0]">
                    {{ errors[0] }}
                  </div>
                  <div class="text-muted" v-if="!errors[0] && isEditable">
                    * Isi password jika ingin mengantinya
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

      isEditable    : false,
      isLoadingForm : false,
      title         : 'Manager Area',
      parameters    : {
        url         : 'managerarea',
                    form : {
                      fullname    : '',
                      username    : '',
                      password    : '',
                      email       : '',
                      role        : 2,
                      description : '',
                      target_copies : '',
                    }
      }
    };
  },

  mounted(){

  },

  computed :{
     ...mapState('modulMaster',['error','result','lookup_province','lookup_city','lookup_district']),
  },

  methods: {
     ...mapActions('modulMaster',['addData','updateData','lookUp']),


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
