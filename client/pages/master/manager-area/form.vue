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
      isStopSearchProvince : false,
      isLoadingGetProvince :  false,
      province_search : '',

      isStopSearchCity : false,
      isLoadingGetCity :  false,
      city_search : '',

      isStopSearchDistrict : false,
      isLoadingGetDistrict :  false,
      district_search : '',

      isEditable  : false,
      isLoadingForm : false,
      title: 'Akun',
      parameters : {
        url : 'account',
        form : {
          fullname    : '',
          username    : '',
          password    : '',
          email       : '',
          role        : '',
          province_id : '',
          city_id     : '',
          district_id : ''
        }
      }
    };
  },

  mounted(){
    (async () => {
        await this.lookUp({
            url : "province",
            lookup : "province",
            query : "?per_page=50"
        });

        await this.lookUp({
            url : "city",
            lookup : "city",
            query : "?per_page=50"
        });

        await this.lookUp({
            url : "district",
            lookup : "district",
            query : "?per_page=50"
        })
    })()
  },

  computed :{
     ...mapState('modulMaster',['error','result','lookup_province','lookup_city','lookup_district']),

     roles(){
      if(!this.$auth.loggedIn) return {};

      let roles = this.$store.state.setting.roles

      switch(this.$auth.user.role){
          case roles.superadmin:
            return this.$store.state.setting.getRoles([roles.manager_nasional,roles.kotele],roles);
          case roles.manager_nasional:
            return this.$store.state.setting.getRoles([roles.admin_nasional,roles.manager_area],roles);
          case roles.manager_area:
            return this.$store.state.setting.getRoles([roles.admin_area,roles.kaper],roles);
          case roles.kaper:
            return this.$store.state.setting.getRoles([roles.admin_kaper,roles.spv],roles);
          case roles.spv:
            return this.$store.state.setting.getRoles([roles.sales],roles);
          case roles.kotele:
            return this.$store.state.setting.getRoles([roles.tele_marketing],roles);
          case roles.admin_nasional:
            return this.$store.state.setting.getRoles([roles.manager_area],roles);
          case roles.admin_area:
            return this.$store.state.setting.getRoles([roles.kaper],roles);
          case roles.admin_kaper:
            return this.$store.state.setting.getRoles([roles.spv],roles);
      }
     },

     isShowProvince(){
       let roles = this.$store.state.setting.roles
       let user = this.$auth.user;

       return [roles.manager_nasional,roles.admin_nasional].includes(user.role) && this.parameters.form.role !== roles.admin_nasional;
     },

     isShowCity(){
       let roles = this.$store.state.setting.roles
       let user = this.$auth.user;

       return [roles.manager_area,roles.admin_area].includes(user.role) && this.parameters.form.role !== roles.admin_area;
     },

     isShowDistrict(){
       let roles = this.$store.state.setting.roles
       let user = this.$auth.user;

       return [roles.kaper,roles.admin_kaper].includes(user.role) && this.parameters.form.role !== roles.admin_kaper;
     }
  },

  methods: {
     ...mapActions('modulMaster',['addData','updateData','lookUp']),

    onGetProvince(search,isNext){
      if(!search.length && typeof isNext === "function") return false;

      clearTimeout(this.isStopSearchProvince);

      this.isStopSearchProvince = setTimeout(() => {
        this.province_search = search;

        if(typeof isNext !== "function"){
          this.lookup_province.current_page = isNext
            ? this.lookup_province.current_page + 1
            : this.lookup_province.current_page - 1;
        }else{
          this.lookup_province.current_page = 1;
        }

        this.onSearchProvince();
      },600);
    },

    async onSearchProvince(){
      if(!this.isLoadingGetProvince){
        this.isLoadingGetProvince = true;

        await this.lookUp({
          url : "province",
          lookup  : 'province',
          query : "?search="+this.province_search+"&page="+this.lookup_province.current_page+"&per_page=50&is_get_school=true"
        })

        this.isLoadingGetProvince= false;
      }
    },

    onGetCity(search,isNext){
      if(!search.length && typeof isNext === "function") return false;

      clearTimeout(this.isStopSearchCity);

      this.isStopSearchCity = setTimeout(() => {
        this.city_search = search;

        if(typeof isNext !== "function"){
          this.lookup_city.current_page = isNext
            ? this.lookup_city.current_page + 1
            : this.lookup_city.current_page - 1;
        }else{
          this.lookup_city.current_page = 1;
        }

        this.onSearchCity();
      },600);
    },

    async onSearchCity(){
      if(!this.isLoadingGetCity){
        this.isLoadingGetCity = true;

        await this.lookUp({
          url : "city",
          lookup  : 'city',
          query : "?search="+this.city_search+"&page="+this.lookup_city.current_page+"&per_page=50&province_id="+(typeof this.form.province_id  === 'object' ? this.form.province_id.id : 0)
        })

        this.isLoadingGetCity = false;
      }
    },

    onGetDistrict(search,isNext){
      if(!search.length && typeof isNext === "function") return false;

      clearTimeout(this.isStopSearchDistrict);

      this.isStopSearchDistrict = setTimeout(() => {
        this.district_search = search;

        if(typeof isNext !== "function"){
          this.lookup_district.current_page = isNext
            ? this.lookup_district.current_page + 1
            : this.lookup_district.current_page - 1;
        }else{
          this.lookup_district.current_page = 1;
        }

        this.onSearchDistrict();
      },600);
    },

    async onSearchDistrict(){
      if(!this.isLoadingGetDistrict){
        this.isLoadingGetDistrict = true;

        await this.lookUp({
          url : "district",
          lookup  : 'district',
          query : "?search="+this.district_search+"&page="+this.lookup_district.current_page+"&per_page=50&city_id="+(typeof this.form.city_id  === 'object' ? this.form.city_id.id : 0)
        })

        this.isLoadingGetDistrict = false;
      }
    },

    async onSubmit(isInvalid){
      if(isInvalid || this.isLoadingForm) return;

      this.isLoadingForm = true;

      let parameters = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          district_id : this.parameters.form.district_id
            ? this.parameters.form.district_id.id : '',
          province_id : this.parameters.form.province_id
            ? this.parameters.form.province_id.id : '',
          city_id     : this.parameters.form.city_id
            ? this.parameters.form.city_id.id : ''
        }
      }

      if(this.isEditable){
        await this.updateData(parameters)
      }else{
        await this.addData(parameters)
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
