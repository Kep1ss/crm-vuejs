export default function ({ app, redirect }) {
  if(!app.$cookiz.get('auth._token.local')){
    app.$auth.$storage.setState("loggedIn", false)
    redirect("/login");
  }else{
	  if(!app.$auth.loggedIn){
  		redirect("/login");
  	}
  }
}
