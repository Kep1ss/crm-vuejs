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
                      <th>Status</th>
                      <th>Province</th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <td>{{ i + 1 }}</td>
                      <td>{{item.name}}</td>
                      <td>
                        <span class="badge badge-danger" v-if="item.is_city">
                          Kota
                        </span>
                        <span class="badge badge-success" v-else>
                          Kabupaten
                        </span>
                      </td>
                      <td>{{item.province ? item.province.name : '-'}}
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

    <filter-section
      :self="this"
      ref="form-filter">
      <template>
       <div class="col-md-12">
          <div class="form-group">
            <label for="is_city">Status</label>
            <select name="is_city" v-model="parameters.params.is_city" class="form-control">
              <option value="">Pilih</option>
              <option value="0">Kabupaten</option>
              <option value="1">Kota</option>
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
    this.$refs['form-option'].isFilter          = true;
    this.$refs["form-option"].isMaintenancePage = false;

    if(this.isSuperAdmin){
      this.$refs["form-option"].isAddData = false;
    }
  },

  data() {
    return {
      title               : 'Kota',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'city',
        type : 'pdf',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          is_city     : ""
        },
        default_params :  {
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          is_city     : ""
        },
        form : {
          checkboxs : []
        },
        loadings : {
          isDelete  : false,
          isRestore : false,
        }
      }
    }
  },


  computed : {
    ...mapState('modulMaster',['data','error','result']),


    roles(){
      return this.$store.state.setting.roles
    },

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
        is_city : 0,
        province : '',
        province_id : ''
      };

      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onEdit(item){
      this.$refs["form-input"].isEditable = true;
      this.$refs["form-input"].parameters.form = {
        ...item,
        province_id : item.province,
      };
      window.$("#modal-form").modal("show");
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onDetail(item){
      this.$refs["modal-detail"].parameters.form = {...item};
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
