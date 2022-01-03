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
                </list-option-section>
              </div>

              <table class="table table-striped table-sm vld-parent"
                ref="formContainer">
                <thead>
                  <tr>
                    <th style="width:5%">No</th>
                    <th style="width:45%">Deskripsi</th>
                    <th>Data</th>
                    <th>User</th>
                  </tr>
                </thead>
                <tbody>

                  <tr v-for="(item,i) in data" :key="i">
                    <td>{{ i+1 }}</td>
                    <td>{{ item.description }}</td>                                   
                    <td>
                      Table : {{ item.properties ? item.properties.table : '-'}} <br/>
                      Id Data : {{item.properties ? item.properties.id : '-'}} <br/>
                      Nama Data : {{item.properties ? item.properties.name : '-'}} <br>
                    </td>
                    <td>
                      {{item.causer ? item.causer.username : '-'}}
                    </td>
                  </tr>
                </tbody>

                <table-data-loading-section
                  :self="this"/>

                <table-data-not-found-section
                  :self="this"/>
              </table>

              <div class="card-title border-top" 
               style="padding-bottom: -100px !important">
                <pagination-section 
                  :self="this" 
                  ref="pagination"></pagination-section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <filter-section
      :self="this" 
      ref="form-filter">
    </filter-section>
  </section>
</template>

<script>
import { mapActions,mapState,mapMutations} from 'vuex'


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
    this.$refs['form-option'].isAddData         = false;
  },
  
  data() {
    return {
      title               : 'Activity',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'activity',
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

  methods : {
    ...mapActions('modulMaster',['getData']),
    ...mapMutations('modulMaster',['set_data']),
    onFormShow(){},

    async onLoad(page = 1){
      if(this.isLoadingData) return;

      this.isLoadingData            = true;
      this.active_page              = page;
      this.parameters.params.order  = 'id'
      this.parameters.params.page   = page
      this.parameters.params.sort   = 'asc'
      this.parameters.url           = 'activity'

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
    }
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