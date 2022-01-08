<template>
  <portal to="modal-detail">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-detail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detail Data</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
     
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="name">Nama</label>
                    <div>{{parameters.form.name}}</div>
                  </div>
                </div>  
                <div class="col">
                  <div class="form-group">
                    <label for="is_city">Status</label>
                    <div>
                      <span class="badge badge-danger" v-if="parameters.form.is_city">
                        Kota
                      </span>
                      <span class="badge badge-success" v-else>
                        Kabupaten
                      </span>
                    </div>
                  </div>
                </div>
                </div>

                  <div class="form-group">
                    <label for="province_id">Province</label> 
                    <div>
                      {{parameters.form.province ? parameters.form.province.name : '-'}}
                    </div>
                  </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
export default {
  middleware : ["isNotAccessable"],
  
  props: ["self"],

  data() {
    return {    
      parameters : {    
        form : {
          name : '',
          province : '',
          is_city : 0
        }
      }
    };
  },

  computed: {
    roles(){      
      return this.$store.state.setting.roles     
     }
  }
};
</script>
