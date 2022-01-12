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
                      <label for="district">Kecamatan</label>                    
                      <div v-if="parameters.form.district">
                        {{parameters.form.district.name}} <br/>
                        <div v-if="parameters.form.district.city">
                          {{parameters.form.district.city.is_city ? 'Kota' : 'Kabupaten'}} : 
                          {{parameters.form.district.city.name}} <br/>
                          <div v-if="parameters.form.district.city.province">
                            Provinsi : {{parameters.form.district.city.province.name}}
                          </div>
                        </div>
                      </div>
                      <div v-else>
                          -
                      </div>       
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">Swasta/Negeri</label>
                  <div>
                    <span v-if="parameters.form.is_private" class="badge badge-danger">
                      Swsta
                    </span>
                    <span v-else class="badge badge-success">
                      Negeri
                    </span>
                  </div>
                </div>                

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="member">Jumlah Murid</label>
                      <div>
                        {{parameters.form.member}}
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="level">Jenjang</label>
                      <div>
                        {{parameters.form.level}}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="address">Alamat</label>
                  <div>
                    {{parameters.form.address || '-'}}
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="phone_headmaster">No Telp Kepala Sekolah</label>
                      <div>
                        {{parameters.form.phone_headmaster || '-'}}
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="phone_teacher">No Telp Guru</label>
                      <div>
                        {{parameters.form.phone_teacher || '-'}}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="phone_treasurer">No Telp Bendahara</label>
                  <div>
                    {{parameters.form.phone_treasurer || '-'}}
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
          district : '',
          district_id : '',
          member : 0,
          level : 'TK',
          is_private : 1,
          address : '',
          phone_headmaster : '',
          phone_teacher : '',
          phone_treasurer : ''    
        }
      }
    };
  }
};
</script>
