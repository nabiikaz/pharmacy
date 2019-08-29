<template>
    <div>
        <div class="modal fade" id="modaleditsupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Edit Supplier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <form action="post" @submit.prevent="editSupplier">
                           <div class="md-form mb-2">


                               <label for="supplier_name">Supplier Name :</label>
                                        <input name="supplier_name" type="text" id="supplier_name" class="form-control "
                                            v-model="supplier.supplier_name" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                               <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="supplier.tel" required pattern="[0-9]{9}|[0-9]{10}">
                            </div>

                             <div class="md-form mb-2">


                               <label for="address">Address</label>
                                        <input name="address" type="text" id="address" class="form-control "
                                            v-model="supplier.address" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                              <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="supplier.email" required>
                            </div>



                            <hr>


                            <div class="md-form mb-2">




                            </div>

                            <hr>
                            <div class="d-flex justify-content-center">
                                <input type="submit" class="btn btn-primary submit-btn " value="submit">

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
</template>


<script>
    export default {
        props: ['supplierId'],


        watch: {
            supplierId:function(Id){
                this.getSupplier(Id)
                this.Message = ""
                this.MessageClass = "text-success"
            }

        },
        data() {
            return {

                Message:"",
                MessageClass:"text-success",
                Img:null,
                
                supplier:{
                    
                    supplier_name: "",
                    tel: "",
                    email: "",
                    

                },




            }


        },

        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
methods: {
            getSupplier: function(supplierId){
                    this.disable = false
                    this.errorMsg = ""
               
                    axios.get('/api/suppliers/'+this.supplierId)
                    .then((Response)=>{
                         this.supplier = Response.data.data
                         

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                                case 422 :                              
                                    this.Message  = Object.values(error.response.data.errors)[0][0]
                                    
                                    this.MessageClass = "text-danger"
                                    return
                                case 404:
                                    break;
                        
                            default:
                                break;
                            }   
                            this.Message = "Pharmacist : "+error.response.statusText
                        }
                    })
               
               

                 
            },

           editSupplier: function (event) {
                /** edit the Supplier using Axios via the server's API
                 * 
                 * 
                 * 
                 * 
                 * 
                 */
                
                

                axios.patch('/api/suppliers/'+this.supplierId,this.supplier)
                    .then((Response)=>{
                        this.ImgFormData = null
                        this.getSupplier(this.supplierId)
                        switch (Response.status) {
                            case 200:


                                this.Message = "Supplier was updated successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.supplier = {}
                               
                                //emit back the edited Medicine : 
                                this.$emit('updated', Response.data.data)


                                
                                break;
                        
                            default:
                                break;
                        }
                         
                

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            console.log(error.response)
                            this.Message = "Error : "+ error.response.data.errors
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                        
                    });
              

            },

        }

    }

</script>


<style scoped>
    body {
        font-family: Roboto, sans-serif;

    }

    label {
        font-size: 14px;
        color: gray;

    }

    .submit-btn {
        width: 400px;
        background-color: #272b30;
    }

</style>
