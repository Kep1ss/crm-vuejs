<template>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="card-title">
                <list-option-section 
                  :self="this" 
                  ref="form-option">
                  <template>
                    <nuxt-link to="/master/school/download" class="btn btn-primary btn-sm"
                      v-if="isCan">
                      <i class="fas fa-download"></i>
                      Download Data Sekolah Dari Dapodik
                    </nuxt-link>
                  </template>
                </list-option-section>                
              </div>

          
              <!-- start table -->
              <div class="table-responsive">
                <table class="table table-striped table-sm vld-parent"
                  ref="formContainer">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th @click="onSort('name',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Nama</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'name' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'name' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>                   
                      <th>Kecamatan</th>
                      <th>Jenjang</th>
                      <th>Swasta/Negeri</th>
                      <th @click="onSort('member',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Jumlah Murid</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'member' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'member' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>           
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">                  
                      <td>{{ i + 1 }}</td>
                      <td>{{item.name}}</td>                      
                      <td>                    
                        <div v-if="item.district">
                          {{item.district.name}} <br/>
                          <div v-if="item.district.city">
                            {{item.district.city.is_city ? 'Kota' : 'Kabupaten'}} : 
                            {{item.district.city.name}} <br/>
                            <div v-if="item.district.city.province">
                              Provinsi : {{item.district.city.province.name}}
                            </div>
                          </div>
                        </div>
                        <div v-else>
                          -
                        </div>                      
                      </td>
                      <td>
                        <span v-if="item.level == 'TK'" class="badge badge-success">
                          TK
                        </span>
                        <span v-else-if="item.level == 'SD'" class="badge badge-primary">
                          SD
                        </span>
                        <span v-else-if="item.level == 'SMP'" class="badge badge-warning">
                          SMP
                        </span>
                        <span v-else-if="item.level == 'SMK'" class="badge badge-danger">
                          SMK
                        </span>
                        <span v-else-if="item.level == 'SMA'" class="badge badge-info">
                          SMA
                        </span>
                        <span v-else-if="item.level == 'SLB'" class="badge badge-danger">
                          SLB
                        </span>
                      </td>
                      <td>
                        <span v-if="item.is_private" class="badge badge-danger">
                          Swasta 
                        </span>
                        <span class="badge badge-success" v-else>
                          Negeri
                        </span>
                      </td>
                      <td>
                        {{item.member}}
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
                          </button>
                          <button class="btn btn-sm btn-primary" @click="onEdit(item)"
                            :disabled="!isCan">
                            <i class="fas fa-pen"></i>
                          </button>                        
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  
                  <table-data-loading-section
                    :self="this"/>

                  <table-data-not-found-section
                    :self="this"/>
                </table>
              </div>
              <!-- end table -->

              <div class="card-title border-top"  
                style="padding-bottom: -100px !important">
                <pagination-section 
                  :self="this" 
                  ref="pagination"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ModalDetail
      :self="this"
      ref="modal-detail"/>

    <FormInput
      :self="this"
      ref="form-input"/>

    <filter-section
      :self="this"
      ref="form-filter">
      <template>
       <div class="col-md-12">
          <div class="form-group">
            <label for="level">Jenjang</label>
            <select class="form-control" v-model="parameters.params.level">
              <option value="">Pilih</option>
              <option value="TK">TK</option>
              <option value="SD">SD</option>
              <option value="SMP">SMP</option>
              <option value="SMA">SMA</option>
              <option value="SMK">SMK</option>
              <option value="SLB">SLB</option>
            </select>
          </div>

        <div class="form-group">
          <label for="is_private">Swasta/Negeri</label>
          <select class="form-control" v-model="parameters.params.is_private">
            <option value="">Pilih</option>
            <option value="0">Negeri</option>
            <option value="1">Swasta</option>
          </select>
        </div>
        </div>
      </template>
    </filter-section>

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'
import FormInput from "./form";
import ModalDetail from "./detail";
import Profil from '../../profil.vue';

export default {
  head() {
    return {
      title: 'Sekolah',
    }
  },

  created() {    
    this.set_data([]);
    this.onLoad();    
  },

  mounted() {
    this.$refs["form-option"].isExport          = false;
    this.$refs['form-option'].isFilter          = true;
    this.$refs["form-option"].isMaintenancePage = false;  
    this.$refs["form-option"].isAddData = this.isCan;    
  },

  data() {
    return {
      title               : 'Sekolah',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'school',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,

          is_private : "",
          level      : ""
        },
        default_params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,

          is_private : "",
          level      : ""
        }
      }    
    }
  },


  computed : {
    ...mapState('modulMaster',['data','error','result']),

    isCan(){
      let roles = this.$store.state.setting.roles;
      return [roles.spv].includes(this.$auth.user.role); 
    }
  },

  components : {
     FormInput,
     ModalDetail  
  },

  methods : {
    ...mapActions('modulMaster',['getData']),

    ...mapMutations('modulMaster',['set_data']),

    onFormShow(){
      this.$refs["form-input"].parameters.form = {
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
      };

      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onEdit(item){
      this.$refs["form-input"].isEditable = true;      
      this.$refs["form-input"].parameters.form = {
        ...item,
        district_id : item.district,
      };
      window.$("#modal-form").modal("show");    
      this.$refs["form-input"].$refs['form-validate'].reset();  
    },

    onDetail(item){
      this.$refs["modal-detail"].parameters.form = {
        ...item
      };
      window.$("#modal-detail").modal("show");
    },

    async onLoad(page = 1){      
      if(this.isLoadingData) return;

      this.isLoadingData            = true;
      this.parameters.params.page   = page

      let loader = this.$loading.show({
        container: this.$refs.formContainer,
        canCancel: true,
        onCancel: this.onCancel,
      });

      await this.getData(this.parameters);

      if(this.result == true){
        loader.hide();

        if (page == 1) {
            this.$refs['pagination'].generatePage();
        }
        this.$refs['pagination'].active_page = this.parameters.params.page;    
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);      
      }  

      this.isLoadingData = false;
    },

    onSort(column,sort = 'asc'){
      this.parameters.params = {
        ...this.parameters.params,
        order : column,
        sort : sort
      }
      
      this.onLoad(this.parameters.params.page)
    }    
  }
}
</script>

<style scoped>
/* 
select.form-control:not([size]):not([multiple]) {
  height: calc(1.5em + .5rem + 2px);
  padding-top: 5px;
  padding-bottom: 5px;
}
*/
</style>