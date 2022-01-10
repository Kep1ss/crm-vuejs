<template>
    <section class="section">
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class="col-12 col-lg-6">
          <div class="card">
            <div class="card-body">
                <h6>Download Data Sekolah Dapodik</h6>

                <ValidationObserver
                    v-slot="{invalid,validate}"
                    ref="form-validate">

                <form @submit.prevent="validate().then(onSubmit(invalid))"
                    autocomplete="off">

                    <div class="row">
                        <div class="col">
                            <ValidationProvider 
                                name="province_id"
                                rules="required">                        
                                <div class="form-group" slot-scope="{errors,valid}">             
                                <label for="province_id">Provinsi</label>                        
                                <input type="hidden"
                                    id="province_id" 
                                    class="form-control" 
                                    name="province_id"                      
                                    v-model="form.province_id"
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/> 
                                                    
                                    <v-select                           
                                    :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                                    label="name"   
                                    :loading="isLoadingGetProvince"
                                    :options="lookup_province.data"
                                    :filterable="false"
                                    @search="onGetProvince"
                                    v-model="form.province_id"
                                    @input="onChangeProvince">              
                                    <li slot-scope="{search}" slot="list-footer"
                                        class="d-flex justify-content-between"
                                        v-if="lookup_province.data.length || search">
                                        <a v-if="lookup_province.current_page > 1" 
                                        @click="onGetProvince(search,false)"
                                        class="flex-fill bg-primary text-white text-center"
                                        href="#">Sebelumnya</a>
                                        <a v-if="lookup_province.last_page > lookup_province.current_page" 
                                        @click="onGetProvince(search,true)"
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

                        <div class="col">
                             <ValidationProvider 
                                name="city_id"
                                rules="required">                        
                                <div class="form-group" slot-scope="{errors,valid}">             
                                <label for="city_id">Kota</label>                        
                                <input type="hidden"
                                    id="city_id" 
                                    class="form-control" 
                                    name="city_id"                      
                                    v-model="form.city_id"
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/> 
                                                    
                                    <v-select                           
                                    :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                                    label="name"   
                                    :loading="isLoadingGetCity"
                                    :options="lookup_city.data"
                                    :filterable="false"
                                    @search="onGetCity"
                                    v-model="form.city_id"
                                    :disabled="!form.province_id"
                                    @input="onChangeCity">              
                                    <li slot-scope="{search}" slot="list-footer"
                                        class="d-flex justify-content-between"
                                        v-if="lookup_city.data.length || search">
                                        <a v-if="lookup_city.current_page > 1" 
                                        @click="onGetCity(search,false)"
                                        class="flex-fill bg-primary text-white text-center"
                                        href="#">Sebelumnya</a>
                                        <a v-if="lookup_city.last_page > lookup_city.current_page" 
                                        @click="onGetCity(search,true)"
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
                    </div>

                    <ValidationProvider 
                        name="district_id"
                        rules="required">                        
                        <div class="form-group" slot-scope="{errors,valid}">             
                        <label for="district_id">Kecamatan</label>                        
                        <input type="hidden"
                            id="district_id" 
                            class="form-control" 
                            name="district_id"                      
                            v-model="form.district_id"
                            :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/>
                                            
                            <v-select                           
                            :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')"                         
                            label="name"   
                            :loading="isLoadingGetDistrict"
                            :options="lookup_district.data"
                            :filterable="false"
                            @search="onGetDistrict"
                            v-model="form.district_id"
                            :disabled="!form.city_id">              
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

                    <div class="row">
                        <div class="col">
                            <ValidationProvider 
                                name="level"
                                rules="required">                        
                            <div class="form-group" slot-scope="{errors,valid}">                        
                                <label for="level">Jenjang</label>
                                <select class="form-control" 
                                    v-model="form.level"
                                    :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')">
                                    <option value="">Pilih</option>
                                    <option value="TK">TK</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMK">SMK</option>
                                    <option value="SLB">SLB</option>
                                </select>
                                <div class="invalid-feedback" v-if="errors[0]">
                                    {{ errors[0] }}
                                </div>   
                            </div>
                            </ValidationProvider>
                        </div>

                        <div class="col">
                            <ValidationProvider 
                                name="year"
                                rules="required">  
                            <div class="form-group" slot-scope="{errors,valid}">                        
                                <label for="year">Tahun</label>
                                <select class="form-control" 
                                    v-model="form.year"
                                    :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')">
                                    <option value="">Pilih</option>
                                    <option v-for="item in years"
                                        :key="item">
                                        {{item}}
                                    </option>
                                    <div class="invalid-feedback" v-if="errors[0]">
                                        {{ errors[0] }}
                                    </div>  
                                </select>
                            </div>
                            </ValidationProvider>
                        </div>
                    </div>

                    <ValidationProvider 
                        name="semester"
                        rules="required">  
                        <div class="form-group" slot-scope="{errors,valid}">                        
                            <label for="semester">Semester</label>
                            <select class="form-control" 
                                v-model="form.semester"
                                :class="errors[0] ? 'border rounded-lg border-danger' : (valid ? 'border rounded-lg border-success' : '')">
                                <option value="">Pilih</option>
                                <option value="2">Semester 2</option>
                                <option value="1">Semester 1</option>
                            </select>
                            <div class="invalid-feedback" v-if="errors[0]">
                                {{ errors[0] }}
                            </div>  
                        </div>
                    </ValidationProvider>

                    <button class="btn btn-primary">
                        <i class="fas fa-list"></i> Ambil Data
                    </button>                    
                </form>
                </ValidationObserver>
            </div>
          </div>
        </div>
      </div>
    </div>
    </section>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {
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

        isLoadingForm : false,

        years : [],
        form : {
            province_id : "",
            city_id     : "",
            district_id : "",
            year        : "",
            semester    : "",
            level       : ""
        }
    }
  },

  created(){
    let year = new Date().getFullYear() - 1;

    for(let i=year-4;i<=year;i++){
        this.years.push(year--);
    }

    (async () => {
        await this.lookUp({
            url : "city/province",
            lookup : "province",
            query : "?per_page=50&is_get_school=true"
        });
    })()
  },

  computed : {
     ...mapState('modulMaster',['lookup_province','lookup_city','lookup_district']),     
  },

  methods : {
    ...mapActions('modulMaster',['lookUp']),

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
          url : "city/province",      
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
          url : "district/city",      
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
          url : "school/district",      
          lookup  : 'district',
          query : "?search="+this.district_search+"&page="+this.lookup_district.current_page+"&per_page=50&city_id="+(typeof this.form.city_id  === 'object' ? this.form.city_id.id : 0)
        })

        this.isLoadingGetDistrict = false;      
      }
    },

    onChangeProvince(event){    
        if(event){
            this.form.city_id = '';

            this.lookUp({
                url : "district/city",
                lookup : "city",
                query : "?per_page=50&province_id="+event.id
            })    
        }
    },

    onChangeCity(event){    
        if(event){
            this.form.district_id = '';

            this.lookUp({
                url : "school/district",
                lookup : "district",
                query : "?per_page=50&city_id="+event.id
            })    
        }
    },

    onSubmit(isInvalid){           
      if(isInvalid || this.isLoadingForm) return;  

      this.isLoadingForm = true;

      let form = {
        ...this.form,
        province_id : this.form.province_id ? this.form.province_id.id : 0,
        city_id : this.form.city_id ? this.form.city_id.id : 0,
        district_id : this.form.district_id ? this.form.district_id.id : 0
      }

      this.$axios.post("/school/get/dapodik",form)
      .then(res => {
        console.log(res);
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => {
        this.isLoadingForm = false;
      })
    }
  }
};
</script>
