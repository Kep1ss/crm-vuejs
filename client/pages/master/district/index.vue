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
                  ref="form-option"/>
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
                      <th>Kota</th>
                      <th>Provinsi</th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">                  
                      <td>{{ i + 1 }}</td>
                      <td>{{item.name}}</td>                      
                      <td>{{item.city ? item.city.name : '-'}}</td>
                      <td>{{item.city ? (item.city.province ? item.city.province.name : '-') : '-'}}                 
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
                          </button>
                          <button class="btn btn-sm btn-primary" @click="onEdit(item)"
                            :disabled="isSuperAdmin">
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

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'
import FormInput from "./form";
import ModalDetail from "./detail";

export default {
  head() {
    return {
      title: 'Kota',
    }
  },

  created() {    
    this.set_data([]);
    this.onLoad();    
  },

  mounted() {
    this.$refs["form-option"].isExport          = false;
    this.$refs['form-option'].isFilter          = false;
    this.$refs["form-option"].isMaintenancePage = false;
      
    if(this.isSuperAdmin){
      this.$refs["form-option"].isAddData = false;
    }
  },

  data() {
    return {
      title               : 'Kecamatan',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'district',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
        }
      }    
    }
  },


  computed : {
    ...mapState('modulMaster',['data','error','result']),

    isSuperAdmin(){
      return this.$auth.user.role === this.$store.state.setting.roles.superadmin
    },    
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
        city : '',
        city_id : ''
      };

      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onEdit(item){
      this.$refs["form-input"].isEditable = true;      
      this.$refs["form-input"].parameters.form = {
        ...item,
        city_id : item.city,
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
        this.$refs['pagination'].generatePage();        
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