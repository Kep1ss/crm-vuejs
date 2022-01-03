export default {    
    data(){
        return {                
            name : '',
            
            /* FOR LOADING PAGE */
            isLoadingPage : false,      
            
            /* FOR PAGINATION */
            pagination : {
                data : [],
                current_page : 1,
                total : 0
            },    
            
            /* FOR SEARCHING */
            search : {
                search : '',        
                per_page : 10,
                order : 'id',
                sort : 'desc',
                soft_deleted : 'normal'
            },
            search_reset : {},

            /* FOR FORM */
            isLoadingForm : false,
            isEditable : false,   
            form_reset : {},

            /* FOR REPORT */
            isLoadingReport : false,
            reportType : '',
            
            /* FOR SEARCHING RELASIONAL */
            per_page : 50,

            /* FOR RESTORE */
            isLoadingRestore : false,
            indexRestore : 0,


            has_download:false,
            
            checkboxs: []
        }
    }
}