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
              <div class="form-group">
                <label for="title">Judul</label>
                <div>{{parameters.form.title}}</div>
              </div>              
              
              <div class="form-group">
                <label for="link">Link</label>
                <div>{{parameters.form.link}}</div>
              </div>               
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {
  middleware : ["isNotAccessable"],
  props: ["self"],
  data() {
    return {    
      parameters : {    
        form : {
          title : '',
          link : ''
        }
      }
    };
  },

};
</script>
