export default {
 	head(){
        return {
            title : this.title
        }
    },	

    data () {
      return {
      	isLoadingDownloadTemplate : false,
      	isLoadingForm : false, 
        errorSelected : {},        
        data : {
            success : [],
            faileds : []
        }
      }
    },

    methods : {
        onOpenReport(){
            window.$("#modal-1").modal("show");
        },

        onSelectedError(item){
            this.errorSelected = item;

            window.$("#modal-1").modal("hide");

            setTimeout(() => {
                window.$("#modal-2").modal("show");
            },500);
        },

        onBackOpenReport(){
            window.$("#modal-2").modal("hide");

            setTimeout(() => {
                window.$("#modal-1").modal("show");
            },500);
        },

        onBackPage(){
            window.$("#modal-1").modal("hide");

            setTimeout(() => {
                this.$router.back();
            },500);
        },

    	onSubmit(){
    		if(this.isLoadingForm){
    			return false;
    		}

			let formData = new FormData(document.querySelector("form[name=form-import]"));
	
			this.isLoadingForm = true;

			this.$axios.post(this.api_url,formData)
			.then(res => {
                document.querySelector("form[name=form-import]").reset(); 

                this.data = res.data;

                if(!res.data.faileds.length){
                    if(res.data.success.length){
                        window.$("#modal-1").modal("show");

				        this.$toaster.success("Berhasil import data");  
                    }else{
                        this.$toaster.warning("Sepertinya data kosong");
                    }
                }else{
                    window.$("#modal-1").modal("show");

                    if(res.data.success.length){
                        this.$toaster.success("Berhasil Import : " + res.data.success.length + " Baris");
                    }

                    this.$toaster.warning("Gagal Import : " + res.data.faileds.length + " Baris");
                }
			})
			.catch(err => {
				console.log(err);

		        if(err.response && err.response.status === 422){
		          this.$toaster.error(err.response.data.error || 'Terjadi Kesalahan');
		        }else if(err.response && err.response.status === 500){
		          this.$toaster.error(err.response.data.error || 'Terjadi Kesalahan');
		        }else{
		          this.$toaster.error('Terjadi Kesalahan');
		        }
			})
			.finally(() => {
				this.isLoadingForm = false;
			})
    	},

    	onDownloadTemplate(){
    		if(this.isLoadingDownloadTemplate){
    			return false;
    		}
			
            this.isLoadingDownloadTemplate = true;
            
    		this.$axios({
    			url : this.api_url,
    			responseType : "blob",        			
    		})
    		.then(res => {
 			  const link = document.createElement('a');
              link.href = window.URL.createObjectURL(new Blob([res.data]));
              link.setAttribute('download', 'template-' + this.$route.name + '.' + 'xlsx');
              document.body.appendChild(link);
              link.click();
    		})
    		.catch(err => {
    		  console.log(err);
 
    	     if(err.response && err.response.status === 422){
    	       this.$toaster.error(err.response.data.error || 'Terjadi Kesalahan');
    	     }else if(err.response && err.response.status === 500){
    	       this.$toaster.error(err.response.data.error || 'Terjadi Kesalahan');
    	     }else{
    	       this.$toaster.error('Terjadi Kesalahan');
    	     }
    		})
    		.finally(() => {
    			this.isLoadingDownloadTemplate = false;
    		})
    	}
    }	
}