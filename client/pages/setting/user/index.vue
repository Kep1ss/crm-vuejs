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

              <!--
                <div v-if="parameters.form.checkboxs.length">
                <button class="btn btn-sm btn-danger"
                  data-toggle="tooltip"
                  data-placement="top"
                  data-original-title="Hapus Semua Data"
                  @click="onDeleteAll()"
                  v-if="parameters.params.soft_deleted != 'deleted'">
                  <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-sm btn-success"
                  data-toggle="tooltip"
                  data-placement="top"
                  data-original-title="Restore Semua Data"
                  @click="onRestoreAll()"
                  v-if="parameters.params.soft_deleted">
                  <i class="fas fa-redo"></i>
                </button>
              </div>
              -->

              <!-- start table -->
              <div class="table-responsive">
                <table class="table table-striped table-sm vld-parent"
                  ref="formContainer">
                  <thead>
                    <tr>
                      <!-- <th><input type="checkbox" id="checkAll" @click="onCheckAll"></th> -->
                      <th>No</th>
                      <th @click="onSort('username',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Username</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                      <th @click="onSort('email',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Email</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'email' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'email' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                      <th>Role</th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <!-- <td><input type="checkbox" name="checkboxs[]" :value="item.id" v-model="parameters.form.checkboxs"></td> -->
                      <td>{{ i + 1 }}</td>
                      <td>{{ item.username }}</td>
                      <td>{{ item.email }}</td>
                      <td>
                        <span v-if="item.role === roles.superadmin" class="badge badge-danger">
                          Super Admin
                        </span>
                        <span v-else-if="item.role === roles.manager_nasional" class="badge badge-success">
                          Manager Nasional
                        </span>
                        <span v-else-if="item.role === roles.manager_area" class="badge badge-success">
                          Manager Area
                        </span>
                        <span v-else-if="item.role === roles.kaper" class="badge badge-primary">
                          Kaper
                        </span>
                        <span v-else-if="item.role === roles.spv" class="badge badge-primary">
                          Spv
                        </span>
                        <span v-else-if="item.role === roles.sales" class="badge badge-info">
                          Sales
                        </span>
                        <span v-else-if="item.role === roles.kotele" class="badge badge-info">
                          Kotele
                        </span>
                        <span v-else-if="item.role === roles.tele_marketing" class="badge badge-info">
                          Tele Markerting
                        </span>
                        <span v-else-if="item.role === roles.admin_nasional" class="badge badge-warning">
                          Admin Nasional
                        </span>
                        <span v-else-if="item.role === roles.admin_area" class="badge badge-warning">
                          Admin Area
                        </span>
                        <span v-else-if="item.role === roles.admin_kaper" class="badge badge-warning">
                          Admin Kaper
                        </span>
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
                          </button>
                          <button class="btn btn-sm btn-primary" @click="onEdit(item)"
                            :disabled="![roles.manager_nasional,roles.kotele].includes(item.role)">
                            <i class="fas fa-pen"></i>
                          </button>
                          <!--
                          <button class="btn btn-sm btn-danger" @click="onTrashed(item)" v-if="!item.deleted_at">
                            <i class="fas fa-trash"></i>
                          </button>
                          <button class="btn btn-sm btn-success" @click="onRestored(item)" v-if="item.deleted_at">
                            <i class="fas fa-redo"></i>
                          </button>
                          -->
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

    <!--
    <filter-section
      :self="this"
      ref="form-filter">
      <template>
       <div class="col-md-12">
          <div class="form-group">
            <label for="role">By Role</label>
            <select name="role" class="form-control"
              v-model="parameters.params.role">
              <option value="all" selected>Pilih</option>
              <option value="0">SuperAdmin</option>
              <option value="1">Manager Area</option>
            </select>
          </div>
        </div>
      </template>
    </filter-section>
    -->

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'
import FormInput from "./form";
import ModalDetail from "./detail";

export default {
  head() {
    return {
      title: 'Akun',
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
  },

  data() {
    return {
      title               : 'Akun',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'user',
        type : 'pdf',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
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
    ...mapState('modulSetting',['data','error','result']),
    roles(){
      return this.$store.state.setting.roles
    },     
  },

  components : {
     FormInput,
     ModalDetail
  },

  methods : {
    ...mapActions('modulSetting',['getData','deleteData','restoreData','deleteAllData','restoreAllData']),

    ...mapMutations('modulSetting',['set_data']),

    onFormShow(){
      this.$refs["form-input"].parameters.form = {
        fullname : '',
        username : '',
        password : '',
        email    : '',
        role     : ''
      };

      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onEdit(item){
      this.$refs["form-input"].isEditable = true;
      this.$refs["form-input"].parameters.form = {...item};
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

      this.parameters.form.checkboxs = [];
      if(document.getElementById("checkAll")){
        document.getElementById("checkAll").checked = false;
      }

      let loader = this.$loading.show({
        container: this.$refs.formContainer,
        canCancel: true,
        onCancel: this.onCancel,
      });

      await this.getData(this.parameters);

      if(this.result == true){
        loader.hide();
        this.$refs['pagination'].active_page = this.parameters.params.page;
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);
      }

      this.isLoadingData = false;
    },

    // onTrashed(item){
    //   if(this.parameters.loadings.isDelete) return

    //   this.$confirm({
    //     auth: false,
    //     message: "Data ini akan dipindahkan ke dalam Trash. Yakin ??",
    //     button: {
    //       no: 'No',
    //       yes: 'Yes'
    //     },
    //     callback: async confirm => {
    //       if (confirm) {
    //         this.parameters.loadings.isDelete = true;

    //         await this.deleteData({
    //           url :  this.parameters.url,
    //           id  :  item.id,
    //           params : this.parameters.params
    //         });

    //         if (this.result == true){
    //           this.onLoad(this.parameters.params.page)
    //           this.$toaster.success("Data berhasil di pindahkan ke dalam Trash!");
    //         }else {
    //           this.$globalErrorToaster(this.$toaster,this.error);
    //         }

    //         this.parameters.loadings.isDelete = false;
    //       }
    //     },
    //   });
    // },

    // async onRestored(item){
    //   if(this.parameters.loadings.isRestore) return

    //   this.parameters.loadings.isRestore = true;

    //   await this.restoreData({
    //     url : this.parameters.url,
    //     id : item.id,
    //     params : this.parameters.params
    //   })

    //   if(this.result == true){
    //     this.onLoad(this.parameters.params.page)
    //     this.$toaster.success("Data berhail di restore")
    //   }else{
    //     this.$globalErrorToaster(this.$toaster,this.error);
    //   }

    //   this.parameters.loadings.isRestore = false;
    // },

    // async onRestoreAll(){
    //   if(!this.parameters.form.checkboxs.length || this.parameters.loadings.isRestore) return

    //   this.parameters.loadings.isRestore = true;

    //   await this.restoreAllData({
    //     url : this.parameters.url,
    //     checkboxs : this.parameters.form.checkboxs,
    //     params : this.parameters.params
    //   })

    //   if(this.result == true){
    //     this.onLoad(this.parameters.params.page)
    //     this.parameters.form.checkboxs = [];
    //     document.getElementById("checkAll").checked = false;
    //     this.$toaster.success("Data berhail di restore")
    //   }else{
    //     this.$globalErrorToaster(this.$toaster,this.error);
    //   }

    //   this.parameters.loadings.isRestore = false;
    // },

    // onDeleteAll(){
    //   if(!this.parameters.form.checkboxs.length || this.parameters.loadings.isDelete) return

    //   this.$confirm({
    //     auth: false,
    //     message: "Semua Data ini akan dipindahkan ke dalam Trash. Yakin ??",
    //     button: {
    //       no: 'No',
    //       yes: 'Yes'
    //     },
    //     callback: async confirm => {
    //       if (confirm) {
    //         this.parameters.loadings.isDelete = true;

    //         await this.deleteAllData({
    //           url :  this.parameters.url,
    //           checkboxs : this.parameters.form.checkboxs,
    //           params : this.parameters.params
    //         });

    //         if (this.result == true){
    //           this.onLoad()
    //           this.parameters.form.checkboxs = [];
    //           document.getElementById("checkAll").checked = false;
    //           this.$toaster.success("Data berhasil di pindahkan ke dalam Trash!");
    //         }else {
    //           this.$globalErrorToaster(this.$toaster,this.error);
    //         }

    //         this.parameters.loadings.isDelete = false;
    //       }
    //     },
    //   });
    // },

    // onCheckAll(evt){
    //   let tmpCheckboxs = [];

    //   document.querySelectorAll("input[name='checkboxs[]']").forEach(item => {
    //     item.checked = evt.target.checked;
    //     if(evt.target.checked){
    //       tmpCheckboxs.push(item.value);
    //     }
    //   })

    //   this.parameters.form.checkboxs = tmpCheckboxs
    // },

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
