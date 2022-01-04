<template>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="card-title">
                <list-option-section :self="this" ref="form-option"></list-option-section>
              </div>
              <!--  start table -->
              <table class="table table-striped table-sm vld-parent"
              ref="formContainer">

                <thead>
                  <tr>
                    <th style="width:5%">No</th>
                    <th style="width:20%">Username</th>
                    <th style="width:45%">Email</th>
                    <th style="width:10%">Role</th>
                    <th style="width:10%" class="text-center">Options</th>
                  </tr>
                </thead>
                <tbody>

                  <tr v-for="(item,i) in data" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td>{{ item.username }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.role || '-'}}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-sm btn-success"><i class="fas fa-info-circle"></i></button>
                        <button class="btn btn-sm btn-primary" @click="onEdit(item)"><i class="fas fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger" @click="onTrashed(item)"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <table-data-loading-section
                :self="this"/>
                <table-data-not-found-section
                :self="this"/>
              </table>
              <!-- end table -->
              <div class="card-title border-top"  style="padding-bottom: -100px !important">
                <pagination-section :self="this" ref="pagination"></pagination-section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <FormInput
      :self="this"
      ref="form-input"></FormInput>
    <filter-section
      :self="this" ref="form-filter"></filter-section>
  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'
import FormInput from "./form";

export default {
   props: {
    self: Object,
    isLoadingPage : Boolean
   },
  created() {
     this.set_data([]);
     this.onLoad();
  },
  mounted() {
    this.$refs['form-option'].isMaintenancePage = false;
    this.$refs['form-option'].isFilter          = false;
  },
  data() {
    return {
      title               : 'User',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
                url : 'user',
                type :'pdf',
                params :{
                        soft_delete : '',
                        search      : '',
                        order       : '',
                        sort        : '',
                        all         : '',
                        per_page    : 10,
                        page        : '',}
                      },
    }
  },


  computed : {
    ...mapState('modulMaster',['data','error','result'],{
      data: state => state.data
    }),
  },
  components : {

    FormInput,

  },

  methods : {
    ...mapActions('modulMaster',['getData','deleteData']),
    ...mapMutations('modulMaster',['set_data']),
    async onLoad(page = 1){

      if(this.isLoadingData) return;
      this.isLoadingData            = true;
      this.active_page              = page;
      this.parameters.params.order  = 'id'
      this.parameters.params.page   = page
      this.parameters.params.sort   = 'asc'
      this.parameters.url           = 'user'

      let loader = this.$loading.show({
                    // Optional parameters
                    container: this.$refs.formContainer,
                    canCancel: true,
                    onCancel: this.onCancel,
                });

      await this.getData(this.parameters);
      this.result == 'true' ? '' : this.$toaster.error(this.error);

      this.isLoadingData            = false;
      loader.hide();
      this.$refs['pagination'].generatePage();

    },

    onFormShow(){
      this.$refs["form-input"].parameters.form = {};
      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].onInitial()

    },

    onEdit(item){

      this.$refs["form-input"].isEditable = true;
      this.$refs["form-input"].form = {},
      this.$refs["form-input"].parameters.form = {...item};
      window.$("#modal-form").modal("show");
      this.$refs["form-input"].onInitial()

    },

    onTrashed(item){

      let self = this
      this.$confirm({
        auth: false,
        message: "Data "+item.username+" akan dipindahkan ke dalam Trash. Yakin ??",
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            await this.deleteData({url:this.parameters.url,id:item.id});
              if (this.result == 'true'){
                this.$toaster.success("Data berhasil di pindahkan ke dalam Trash!");
              }else {
                this.$toaster.error(this.error);
                }
          }
        },
      });
    },
  }
}
</script>

<style scoped>
select.form-control:not([size]):not([multiple]) {
    height: calc(1.5em + .5rem + 2px);
    padding-top: 5px;
    padding-bottom: 5px;
}

</style>
