<template>
  <div class="row">
    <div class="col-md-12">
      <h4>{{self.title}}</h4>
    </div>
    <div class="col-md-12" style="margin-top:-10px;">
      <div class="row">
        <!-- Pages -->
        <div class="col-md-1 mt-2" v-if="isShowingPage">
            <select class="form-control form-control-sm"
                   style="padding-left:2px;padding-right:0px;"
                   v-model=self.parameters.params.per_page
                   @change="self.onLoad()">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
            </select>
        </div>
        <!-- Searching -->
        <div class="col-md-4 mt-2" v-if="isSearching">
          <div class="row">
            <div class="col-md-12">
              <input type="text"
                class="form-control form-control-sm"
                placeholder="search..." v-model="self.parameters.params.search"
                @keyup.enter="self.onLoad()">
            </div>
          </div>
        </div>

        <div class="col-md-7 mt-2">
          <!-- Maintenance Page (All, Active, Trashed) -->
          <div class="btn-group" v-if="isMaintenancePage">
            <button class="btn btn-sm btn-primary" @click="getData()" data-toggle="tooltip" data-placement="top" data-original-title="All record's"><i class="fas fa-font"></i><span class="badge badge-danger">{{total}}</span></button>
            <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Active data"><i class="fas fa-list"></i><span class="badge badge-danger">999</span></button>
            <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Trashed"><i class="fas fa-trash"></i><span class="badge badge-primary">999</span></button>
          </div>
          <!-- Filter -->
          <div class="btn-group" v-if="isFilter">
            <button class="btn btn-sm btn-info"
              data-toggle="modal"
              data-target="#modal-filter"><i class="fas fa-filter"></i> Filter</button>
          </div>
          <!-- Add data -->
          <div class="btn-group" v-if="isAddData">
            <button class="btn btn-sm btn-primary"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Add data"
              @click="self.onFormShow"><i class="fas fa-plus fa-spin"></i> Add</button>
          </div>
          <!-- Export (Excel, Pdf, Print) -->
          <div class="btn-group float-right" v-if="isExport">
            <button class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="All record's" @click="onExport('excel')"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="All record's" @click="onExport('pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="All record's" @click="printFile(self.title.toLowerCase())"><i class="fas fa-print"></i> Print</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState,mapActions} from 'vuex'
export default {
  props: {
    self: Object,
    title: String,

  },
  data() {
    return {
      isMaintenancePage   : true,
      isSearching         : true,
      isFilter            : true,
      isExport            : true,
      isAddData           : true,
      isShowingPage       : true,

    }
  },

  computed: {
    ...mapState('pagination',['total'])
  },

  methods: {
    ...mapActions('print',['printFile','exportFile']),
    ...mapActions('ModulMaster',['getData']),

    onExport(type){
        this.self.parameters.type = type;
        this.self.parameters.params.all  = true;
        this.exportFile(this.self.parameters);
    },

    //onSearc

  },
};
</script>
