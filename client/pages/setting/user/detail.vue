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
                    <label for="fullname">Nama Lengkap</label>
                    <div>{{parameters.form.fullname}}</div>
                  </div>
                </div>

                <div class="col">                  
                    <div class="form-group">
                      <label for="uername">Username</label>
                      <div>{{parameters.form.username}}</div>
                    </div>                  
                </div>
              </div>
              
             <div class="form-group">
                <label for="email">Email</label>
                <div>{{parameters.form.email}}</div>
             </div>     

             <div class="form-group">
              <label for="role">Role</label>
              <div>              
                <span v-if="parameters.form.role === roles.superadmin" class="badge badge-danger">
                  Super Admin
                </span>      
                <span v-else-if="parameters.form.role === roles.manager_nasional" class="badge badge-success">
                  Manager Nasional
                </span>          
                <span v-else-if="parameters.form.role === roles.manager_area" class="badge badge-success">
                  Manager Area
                </span>
                <span v-else-if="parameters.form.role === roles.kaper" class="badge badge-primary">
                  Kaper
                </span>
                <span v-else-if="parameters.form.role === roles.spv" class="badge badge-primary">
                  Spv
                </span>
                <span v-else-if="parameters.form.role === roles.sales" class="badge badge-info">
                  Sales
                </span>
                <span v-else-if="parameters.form.role === roles.kotele" class="badge badge-info">
                  Kotele
                </span>
                <span v-else-if="parameters.form.role === roles.tele_marketing" class="badge badge-info">
                  Tele Markerting
                </span>
                <span v-else-if="parameters.form.role === roles.admin_nasional" class="badge badge-warning">
                  Admin Nasional
                </span>
                <span v-else-if="parameters.form.role === roles.admin_area" class="badge badge-warning">
                  Admin Area
                </span>
                <span v-else-if="parameters.form.role === roles.admin_kaper" class="badge badge-warning">
                  Admin Kaper
                </span>              
              </div>
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
          fullname : '',
          username : '',        
          email    : '',
          role     : ''
        }
      }
    };
  },

  computed: {
    roles(){
      if(!this.$auth.loggedIn) return {};
      
      let roles = this.$store.state.setting.roles
      
      switch(this.$auth.user.role){            
          case roles.superadmin:                         
            return this.$store.state.setting.getRoles([roles.manager_nasional,roles.kotele],roles);                    
          case roles.manager_nasional:
            return this.$store.state.setting.getRoles([roles.admin_nasional,manager_area],roles);
          case roles.manager_area:
            return this.$store.state.setting.getRoles([roles.admin_area,roles.kaper],roles);
          case roles.kaper:
            return this.$store.state.setting.getRoles([roles.admin_kaper,roles.spv],roles);
          case roles.spv:
            return this.$store.state.setting.getRoles([roles.sales],roles);
          case roles.kotele:
            return this.$store.state.setting.getRoles([roles.tele_marketing],roles);
          case roles.admin_nasional:
            return this.$store.state.setting.getRoles([roles.manager_area],roles);
          case roles.admin_area:
            return this.$store.state.setting.getRoles([roles.kaper],roles);
          case roles.admin_kaper:
            return this.$store.state.setting.getRoles([roles.spv],roles);
      }        
     }
  }

};
</script>
