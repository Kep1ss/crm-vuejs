<template>
  <portal to="modal-filter">
    <div
      class="modal modal-outer right-modal fade"
      id="modal-filter"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalFilter"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 style="margin-left:-10px">Filter</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
            <span aria-hidden="true">&times;</span>
            </button>
          </div>

            <div class="modal-body">
              <slot></slot>            
              <hr>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <button class="btn btn-warning" type="button"  @click="onReset()"><i class="fas fa-redo-alt"></i> Reset</button>
                    <button class="btn btn-primary float-right"  @click="onFilter()"><i class="fas fa-check-circle"></i> Apply</button>
                  </div>
                </div>
              </div> 
            </div>

        </div>
      </div>
    </div>
  </portal>
</template>
<script>
import {mapState,mapActions} from 'vuex'
export default {
  props: {
    self: Object,
    title: String,
  },

  data() {
    return {
      filter:{
        range    : {
                  start_date :'',
                  end_date   :'',
        },
        division : '',
        position : '',
        gender   : '',
        graduate : '',
        city     : '',
      },
      isDivisionFilter    : true,
      isDaterangeFilter   : true,
      isPositionFilter    : true,
      isGraduationFilter  : true,
      isGenderFilter      : true,
      isCityFilter        : true,
    }
  },

  computed :{
    ... mapState('modulMaster',
    ['result','error','lookup_division',
    'lookup_position','lookup_cites'],{
      lookup_division: state => state.lookup_division,
      lookup_position: state => state.lookup_position ,
      lookup_cites  : state => state.lookup_cites ,
    })
  },

  methods: {
    ... mapActions('modulMaster',['getData']),

     async onFilter(){
      this.self.parameters.params = {
        ... this.self.parameters.params,
        filter : this.filter
      }
      this.self.onLoad();
      window.$("#modal-filter").modal("hide")
    },

    onReset(){
      this.self.parameters.params = {      
        ... this.self.parameters.params,
        filter : ''
      }
      this.self.onLoad();
      window.$("#modal-filter").modal("hide")
    },
  },
};
</script>
<style scoped>
select.form-control:not([size]):not([multiple]) {
  height: calc(2.25rem + -5px);
}
</style>
