export default {
    methods : {
        /* ONLOAD */
        onLoad(page = 1){
            if(this.isLoadingPage){
              return false;
            }
      
            this.isLoadingPage = true;
      
            this.$axios.get(this.api_url,{
              params : {
                ...this.search,
                page : this.isLoadingForm ? this.pagination.current_page : page
              }
            })
            .then(res => {
              this.checkboxs = [];
              this.pagination = {
                 ...res.data 
              };
            })
            .catch(err => {
              this.$toaster.error("Terjadi Kesalahan");
              console.log(err);
            })
            .finally(() => {
              this.isLoadingPage = false;  
            })
        },
        
        /* ONSORT */
        onSort(order,sort){
            this.search = {
              ...this.search,
              order : order,
              sort : sort
            }
      
            this.onLoad();
        },
        
        /* ONRESET */
        onReset(){      
            this.search = Object.assign({},this.search_reset);
            this.onLoad();
        },
        
        /* ONADD */
        onAdd(){
            this.isEditable = false;
            this.$refs["main-form"].$refs['form-validate'].reset();
            this.form = Object.assign({},this.form_reset);            
            window.$("#modal-1").modal("show");
            this.onGetCode();
        },
        
        /* ONEDIT */
        onEdit(item){
            this.isEditable = true;
            this.$refs["main-form"].$refs['form-validate'].reset();
            this.form = Object.assign({},this.form_reset);
            this.form = {...item};
            window.$("#modal-1").modal("show");
        },

        /* ONSUBMIT */
        onSubmit(isInvalid){	
            if(isInvalid || this.isLoadingForm){
              return false;
            }      
    
            this.isLoadingForm = true;
    
            this.$axios({
              method : this.isEditable ? "put" : "post",
              url : this.isEditable ? (this.api_url + this.form.id) : this.api_url,
              data : {...this.form,}
            })
            .then(res => {    
              this.onLoad();
              this.$toaster.success("Data berhasil di " + (this.isEditable ? "Edit" : "Tambah"));          
              window.$("#modal-1").modal("hide");
            }).catch(err => {
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
        
        /* ONREPORT */
        onReport(type){      
            if(this.isLoadingReport){
              return false;
            }
      
            this.isLoadingReport = true;
            this.reportType = type;
            
            this.$axios({
              url : this.api_url + "export/" + type,
              responseType : "blob",
              params : {
                ...this.search
              }
            }).then(res => {          
              const link = document.createElement('a');
              link.href = window.URL.createObjectURL(new Blob([res.data]));
              link.setAttribute('download', 'report-' + this.$route.name + '.' + (type == "excel" ? "xlsx" : type));
              document.body.appendChild(link);
              link.click();
            }).catch(err => {
              console.log(err)
              this.$toaster.error("Terjadi Kesalahan");
            }).finally(() => {
              this.isLoadingReport = false;
              this.reportType = '';
            });
        },
        
        /* ONDELETE */
        onDelete(item){
          Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Menghapus Data Ini",
            icon: "warning",
            confirmButtonColor: "#f1261f",
            confirmButtonText: "Delete",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.isLoadingForm = true;
                return this.$axios.delete(this.api_url + item.id)
                .catch(err => {      
                  this.isLoadingForm = false;
                  console.log(err);     
                  if(err.response && err.response.status === 422){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else if(err.response && err.response.status === 500){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else{
                    Swal.fire("Failed","Terjadi Kesalahan","warning");
                  }                    
                  return false;
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then(({value}) => {        
            if (value) {          
                this.onLoad();
                this.isLoadingForm = false;
                Swal.fire("Deleted!", "Data Berhasil Di Hapus", "success");
            }
          });
        },

        onForceDelete(item){
          Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Menghapus Data Ini Secara Permanent",
            icon: "warning",
            confirmButtonColor: "#f1261f",
            confirmButtonText: "Delete",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.isLoadingForm = true;
                return this.$axios.delete(this.api_url + "force-destroy/" + item.id)
                .catch(err => {      
                  this.isLoadingForm = false;
                  console.log(err);     
                  if(err.response && err.response.status === 422){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else if(err.response && err.response.status === 500){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else{
                    Swal.fire("Failed","Terjadi Kesalahan","warning");
                  }                    
                  return false;
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then(({value}) => {        
            if (value) {          
                this.onLoad();
                this.isLoadingForm = false;
                Swal.fire("Deleted!", "Data Berhasil Di Hapus", "success");
            }
          });
        },

        onDeleteAll(){
          if(!this.checkboxs.length){
            return false;
          }

          Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Menghapus Semua Data Tersebut",
            icon: "warning",
            confirmButtonColor: "#f1261f",
            confirmButtonText: "Delete",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.isLoadingForm = true;
                return this.$axios.delete(this.api_url + "destroy-all",{
                  data : {
                    checkboxs : this.checkboxs
                  }
                })
                .catch(err => {      
                  this.isLoadingForm = false;
                  console.log(err);     
                  if(err.response && err.response.status === 422){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else if(err.response && err.response.status === 500){
                    Swal.fire("Failed",err.response.data.error || 'Terjadi Kesalahan',"warning");
                  }else{
                    Swal.fire("Failed","Terjadi Kesalahan","warning");
                  }                    
                  return false;
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then(({value}) => {        
            if (value) {          
                this.checkboxs = [];
                this.onLoad();
                this.isLoadingForm = false;
                Swal.fire("Deleted!", "Data Berhasil Di Hapus", "success");
            }
          });
        },

        /* ONSEARCH */
        onSearch(evt){
          if((evt.keyCode == 13 || evt.key == "Enter") || !this.search.search){
            this.onLoad();
          }               
        },

        /* ONRESTORE */
        onRestore(item,index){
          if(this.isLoadingRestore){
            return false;
          }
    
          this.isLoadingRestore = true;
          this.indexRestore = index;

          this.$axios.post(this.api_url+"restore/"+item.id)
          .then(res => {
            this.$toaster.success("Berhasil Restore Data");
            this.onLoad();
          })
          .catch(err => {
            this.$toaster.error("Terjadi Kesalahan");
            console.log(err);
          })
          .finally(() => {
            this.isLoadingRestore = false;  
            this.indexRestore = 0;
          })
        },

        onRestoreAll(){
          if(this.isLoadingRestore || !this.checkboxs.length){
            return false;
          }
    
          this.isLoadingRestore = true;
          this.indexRestore = 'none';

          this.$axios.post(this.api_url + "restore-all",{
            checkboxs : this.checkboxs
          })
          .then(res => {
            this.$toaster.success("Berhasil Restore Data");
            this.checkboxs = [];
            this.onLoad();
          })
          .catch(err => {
            this.$toaster.error("Terjadi Kesalahan");
            console.log(err);
          })
          .finally(() => {
            this.isLoadingRestore = false;  
            this.indexRestore = 0;
          })
        },

        formatPrice(value) {
          let val = (value/1).toFixed(2).replace('.', ',')
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        countAble(bilangan) {        
        if(String(bilangan) === "0.00"){
          return bilangan;
        }

         bilangan    = String(parseInt(bilangan));
         var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
         var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
         var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

         var panjang_bilangan = bilangan.length;

         /* pengujian panjang bilangan */
         if (panjang_bilangan > 15) {
           var kaLimat = "Diluar Batas";
           return kaLimat;
         }

         /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
         for (var i = 1; i <= panjang_bilangan; i++) {
           angka[i] = bilangan.substr(-(i),1);
         }

         var i = 1;
         var j = 0;
         var kaLimat = "";


         /* mulai proses iterasi terhadap array angka */
         while (i <= panjang_bilangan) {

           var subkaLimat = "";
           var kata1 = "";
           var kata2 = "";
           var kata3 = "";

           /* untuk Ratusan */
           if (angka[i+2] != "0") {
             if (angka[i+2] == "1") {
               kata1 = "Seratus";
             } else {
               kata1 = kata[angka[i+2]] + " Ratus";
             }
           }

           /* untuk Puluhan atau Belasan */
           if (angka[i+1] != "0") {
             if (angka[i+1] == "1") {
               if (angka[i] == "0") {
                 kata2 = "Sepuluh";
               } else if (angka[i] == "1") {
                 kata2 = "Sebelas";
               } else {
                 kata2 = kata[angka[i]] + " Belas";
               }
             } else {
               kata2 = kata[angka[i+1]] + " Puluh";
             }
           }

           /* untuk Satuan */
           if (angka[i] != "0") {
             if (angka[i+1] != "1") {
               kata3 = kata[angka[i]];
             }
           }

           /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
           if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
             subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
           }

           /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
           kaLimat = subkaLimat + kaLimat;
           i = i + 3;
           j = j + 1;

         }

         /* mengganti Satu Ribu jadi Seribu jika diperlukan */
         if ((angka[5] == "0") && (angka[6] == "0")) {
           kaLimat = kaLimat.replace("Satu Ribu","Seribu");
         }

         return kaLimat + "Rupiah";
        },

        capitalize(string){                  
          return string.toString() != '-' 
            ? string.toString().charAt(0).toUpperCase() + string.toString().slice(1) 
            : '-';        
        },
        
        toLocal(strDate){
          return moment(strDate,'YYYY-M-D').format('D/M/YYYY');
        },
    }
}
