<template>
  <section class="section">
    <div class="section-header">
      <h1>Profile</h1>
    </div>

    <div class="section-body">
      <h2 class="section-title">Hi, {{$auth.user.name}}!</h2>

      <p class="section-lead">
        Change information about yourself on this page.
      </p>

      <div class="row mt-sm-4">
        <div class="col-8">
          <div class="card">
            <ValidationObserver
              v-slot="{invalid,validate}"
              ref="form-validate">
              <form @submit.prevent="validate().then(onSubmit(invalid))"
                autocomplete="off">
                <div class="card-header">
                  <h4>Edit Profile</h4>
                </div>

                <div class="card-body">
                    <div class="row p-0 m-0">
                      <ValidationProvider
                          name="name"
                          rules="required">
                          <div class="form-group col" slot-scope="{errors,valid}">
                            <label>Nama</label>
                            <input type="text"
                              class="form-control"
                              v-model="form.name"
                              name="name"
                              :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/>
                             <div class="invalid-feedback" v-if="errors[0]">
                              {{ errors[0] }}
                            </div>
                          </div>
                      </ValidationProvider>

                      <div class="form-group col">
                        <label>Nama Lengkap</label>
                        <input type="text"
                          class="form-control is-valid"
                          v-model="form.fullname"
                          name="fullname">
                      </div>
                    </div>

                     <ValidationProvider
                        name="email"
                        rules="required|email">
                        <div class="form-group col-12" slot-scope="{errors,valid}">
                          <label>Email</label>
                          <input type="text"
                            class="form-control"
                            v-model="form.email"
                            name="email"
                            :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/>
                           <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>
                        </div>
                    </ValidationProvider>
                </div>

                <div class="card-footer text-right">
                  <button class="btn btn-primary"
                    :disabled="isLoadingForm">
                    <span v-if="isLoadingForm">
                      <i class="fas fa-circle-notch fa-spin"></i>
                    </span>
                    <span v-else>
                      Kirim
                    </span>
                  </button>
                </div>
              </form>
            </ValidationObserver>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  head() {
    return {
      title: 'Profil',
    }
  },

  data(){
    return {
      form : {
        name : '',
        fullname : '',
        email : ''
      },
      isLoadingForm : false
    }
  },

  created(){
    this.form = {
      name : this.$auth.user.name,
      fullname : this.$auth.user.fullname,
      email : this.$auth.user.email
    }
  },

  methods : {
    onSubmit(isInvalid){
      if(isInvalid || this.isLoadingForm) return;

      this.isLoadingForm = true;

      this.$axios.put("/profil",this.form)
      .then(() => {
        return this.$auth.fetchUser();
      })
      .then(() => {
        this.$toaster.success("Berhasil update data");
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingForm = false;
      });
    }
  }
}
</script>
