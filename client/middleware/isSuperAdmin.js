export default function ({ app, redirect, store }) {
	if(app.$auth.user.role !== store.state.setting.roles.superadmin){
  		redirect("/");  
  }
}
