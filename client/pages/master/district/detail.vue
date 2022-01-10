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
                  <label for="name">Nama</label>
                  <div>{{parameters.form.name}}</div>
                </div>     
                <div class="form-group">
                  <label for="city">Kota</label>
                  <div>{{parameters.form.city ? parameters.form.city.name : '-'}}</div>
                </div>                     
                <div class="form-group">
                  <label for="province">Provinsi</label> 
                  <div>
                    {{parameters.form.city ? (parameters.form.city.province ? parameters.form.city.province.name : '-') : '-'}}
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
          city : '',
          city_id : '',      
        }
      }
    };
  }
};
</script>
