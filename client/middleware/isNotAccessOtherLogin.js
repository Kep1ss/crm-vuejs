export default function ({ app, redirect }) {
    if(!app.$cookiz.get("isNotAccessOtherLogin")){
      redirect("/");
    }
  }
  