<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog modal-lg">
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
                  <ValidationProvider
                    name="name"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="name">Nama</label>
                      <input id="name"
                        type="text"
                        class="form-control"
                        name="name"
                        v-model="parameters.form.name"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>      
                    </div>
                  </ValidationProvider>                                                         
                </div>

                <!-- 
                <div class="col">
                  <ValidationProvider 
                    name="district_id"
                    rules="required">                        
                    <div class="form-group" slot-scope="{errors,valid}">             
                      <label for="district_id">Kecamatan</label>                        
                      <input type="hidden"
                        id="district_id" 
                        class="form-control" 
                        name="district_id"                      
                        v-model="parameters.form.district_id"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/> 
                                        
                        <v-select                           
                          :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                          label="name"   
                          :loading="isLoadingGetDistrict"
                          :options="lookup_district.data"
                          :filterable="false"
                          @search="onGetDistrict"
                          v-model="parameters.form.district_id">              
                          <li slot-scope="{search}" slot="list-footer"
                            class="d-flex justify-content-between"
                            v-if="lookup_district.data.length || search">
                            <a v-if="lookup_district.current_page > 1" 
                              @click="onGetDistrict(search,false)"
                              class="flex-fill bg-primary text-white text-center"
                              href="#">Sebelumnya</a>
                            <a v-if="lookup_district.last_page > lookup_district.current_page" 
                              @click="onGetDistrict(search,true)"
                              class="flex-fill bg-primary text-white text-center"
                              href="#">Selanjutnya</a>
                          </li> 
                        </v-select>

                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>                
                    </div>                           
                  </ValidationProvider> 
                </div>
                -->
              </div>

              <div class="form-group">
                <label for="is_private">Swasta/Negeri</label>
                <select class="form-control" name="is_private" v-model="parameters.form.is_private">
                  <option value="0">Negeri</option>
                  <option value="1">Swasta</option>
                </select>
              </div>

              <div class="row">
                <div class="col">
                  <ValidationProvider
                    name="member"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="member">Jumlah Murid</label>
                      <input id="member"
                        type="text"
                        class="form-control"
                        name="member"
                        v-model="parameters.form.member"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')">

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>      
                    </div>
                  </ValidationProvider> 
                </div>

                <div class="col">           
                  <div class="form-group">
                    <label for="level">Jenjang</label>
                    <select name="level" class="form-control" v-model="parameters.form.level">
                      <option value="TK">TK</option>
                      <option value="SD">SD</option>
                      <option value="SMP">SMP</option>
                      <option value="SMA">SMA</option>
                      <option value="SMK">SMK</option>
                      <option value="SLB">SLB</option>
                    </select>
                  </div>
                </div>
              </div>

                <div class="form-group">
                  <label for="address">Alamat</label>
                  <textarea class="form-control" v-model="parameters.form.address"></textarea>
                </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="phone_headmaster">No Telp Kepala Sekloah</label>
                    <input type="text" name="phone_headmaster" class="form-control" v-model="parameters.form.phone_headmaster"/>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="phone_teacher">No Telp Guru</label>
                    <input type="text" name="phone_teacher" class="form-control" v-model="parameters.form.phone_teacher"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="phone_treasurer">No Telp Bendahara</label>
                <input type="text" name="phone_treasurer" class="form-control" v-model="parameters.form.phone_treasurer"/>
              </div>              
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
  mounted(){
    this.lookUp({    
      url : "school/district",      
      lookup  : 'district',
      query : "?per_page=50"
    })
  },

  middleware : ["isNotAccessable"],

  props: ["self"],

  data() {
    return {
      isEditable  : false,
      isLoadingForm : false,
      isStopSearchDistrict : false,
      isLoadingGetDistrict :  false,
      district_search : '',
      title: 'Sekolah',      
      parameters : {
        url : 'school',
        form : {
          name : '',
          district : '',
          district_id : '',
          member : 0,
          level : 'TK',
          is_private : 1,
          address : '',
          phone_headmaster : '',
          phone_teacher : '',
          phone_treasurer : ''
        }
      }
    };
  },

  computed :{
     ...mapState('modulMaster',['error','result','lookup_district']),     
  },

  methods: {    
     ...mapActions('modulMaster',['addData','updateData','lookUp']),

     async onSubmit(isInvalid){           
      if(isInvalid || this.isLoadingForm) return;            
      
      let parameters  = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          district_id : typeof this.parameters.form.district_id  === 'object' 
            ? this.parameters.form.district_id.id  
            : this.parameters.form.district_id
        }
      }

      this.isLoadingForm = true;

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
          url : "school/district",      
          lookup  : 'district',
          query : "?search="+this.district_search+"&page="+this.lookup_district.current_page+"&per_page=50"
        })

        this.isLoadingGetDistrict = false;      
      }
    }
  },
};
</script>
