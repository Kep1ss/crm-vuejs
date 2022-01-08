<template>
  <!-- HEADER SECTION -->
  <div class="section-header">
    <div class="clearfix col-12">
      <div class="float-left">
        <h6>Data {{self.title}}</h6>
        <p>
          Berikut adalah data {{self.title}} yang tersimpan di database.
        </p>
      </div>
      <div class="float-right" v-if="self.onAdd">
        <button class="btn btn-primary"
          @click="self.onAdd">
          <i class="fas fa-plus"></i> 
          Tambah Data
        </button>
      </div>
      <div class="float-right" v-if="self.has_download">
        <button class="btn btn-primary"
          @click="ConvertToCSV(efaktur_reseller.data)">
          <i class="fas fa-plus"></i> 
          Download
        </button>
      </div>
    </div>
  </div>
  <!-- HEADER SECTION -->
</template>
<script>

import { mapState } from "vuex";
export default {
  props : {
    self : Object,
    // title : String,
    // onadd : Function
  },
  computed: {
    ...mapState("etax", {
      efaktur_reseller: state => state.efaktur_reseller
    })  
  },
}
</script>