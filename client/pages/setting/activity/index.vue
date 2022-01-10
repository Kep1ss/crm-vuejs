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
                      <th>Username</th>
                      <th>Deskripsi</th>
                      <th>Detail</th>
                      <th>Dibuat</th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <td>{{ i + 1 }}</td>
                      <td>{{ item.causer ? item.causer.username : '-'}}</td>
                      <td>{{ item.description }}</td>
                      <td>
                        Table : {{ item.properties ? item.properties.table : '-'}}   <br/>
                        Nama : {{ item.properties ? item.properties.name : '-' }}
                      </td>
                      <td>
                        {{onHumanReadAble(item.created_at)}}
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
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

    <filter-section
      :self="this"
      ref="form-filter">
      <template>
       <div class="col-md-12">
          <div class="form-group">
            <label for="created_at">Dibuat Pada</label>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="start_date">Awal</label>
                  <input type="date" class="form-control" v-model="parameters.params.start_date">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="end_date">Akhir</label>
                  <input type="date" class="form-control" v-model="parameters.params.end_date">
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </filter-section>

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'
import ModalDetail from "./detail";

export default {
  created() {
    this.set_data([]);
    this.onLoad();
  },

  mounted() {
    this.$refs["form-option"].isExport          = false;
    this.$refs['form-option'].isFilter          = true;
    this.$refs["form-option"].isAddData         = false;
    this.$refs["form-option"].isMaintenancePage = false;
  },

  data() {
    return {
      title               : 'Log Aktivitas',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'activity',
        type : 'pdf',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          start_date  : '',
          end_date    : ''
        },
        default_params : {
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
  },

  components : {
     ModalDetail
  },

  methods : {
    ...mapActions('modulSetting',['getData']),

    ...mapMutations('modulSetting',['set_data']),

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

    onSort(column,sort = 'asc'){
      this.parameters.params = {
        ...this.parameters.params,
        order : column,
        sort : sort
      }

      this.onLoad(this.parameters.params.page)
    },

    onHumanReadAble(date){
      return moment(date).locale("id").format("LLLL")
    },

    onFormShow(){}
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
