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
              <!-- Filter start - end join date -->
              <div class="row" v-if="isDaterangeFilter">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="start_date">Start date</label>
                    <input type="date" name="start_date" id="start_date" v-model="filter.range.start_date" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_date">End date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" v-model="filter.range.end_date">
                  </div>
                </div>
              </div>
              <!-- Filter Division  -->
              <div class="row" v-if="isDivisionFilter">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="division_filter">By division</label>
                    <select name="division_filter" id="division_filter" class="form-control" v-model="filter.division">
                      <option value="" selected>&mdash; Pilih &mdash;</option>
                      <option  v-for="(item,i) in lookup_division" :key="i"> {{item.name}}</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Filter by Position -->
              <div class="row" v-if="isPositionFilter">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="position_filter">By position</label>
                    <select name="position_filter" id="position_filter" class="form-control" v-model="filter.position">
                      <option value="" selected>&mdash; Pilih &mdash;</option>
                      <option  v-for="(item,i) in lookup_position" :key="i" > {{item.name}}</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Filter bt Graduation -->
              <div class="row" v-if="isGraduationFilter">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="position_filter">Graduation</label>
                    <select name="position_filter" id="position_filter" class="form-control" v-model="filter.graduate">
                      <option value="" selected>&mdash; Pilih &mdash;</option>
                      <option value="SD">SD</option>
                      <option value="SMP">SMP</option>
                      <option value="SMA">SMA</option>
                      <option value="D1">D1</option>
                      <option value="D2">D2</option>
                      <option value="D3">D3</option>
                      <option value="D4">D4</option>
                      <option value="S1">S1</option>
                      <option value="S2">S2</option>
                      <option value="S3">S3</option>

                    </select>
                  </div>
                </div>
              </div>

              <!-- Filter by Gender -->
              <div class="row" v-if="isGenderFilter">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="position_filter">Gender</label>
                    <select name="position_filter" id="position_filter" class="form-control" v-model="filter.gender">
                      <option value="" selected>&mdash; Pilih &mdash;</option>
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>

                    </select>
                  </div>
                </div>
              </div>

              <!-- Filter by City -->
              <div class="row" v-if="isCityFilter">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="position_filter">City</label>
                    <select name="position_filter" id="position_filter" class="form-control" v-model="filter.city">
                      <option value="" selected>&mdash; Pilih &mdash;</option>
                      <option  v-for="(item,i) in lookup_cites" :key="i"> {{item.city}}</option>
                    </select>
                  </div>
                </div>
              </div>
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
        filter : this.filter}

      this.self.onLoad();
      window.$("#modal-filter").modal("hide")


    },
    onReset(){
      this.self.parameters.params = {
        ... this.self.parameters.params,
        filter : ''}
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
