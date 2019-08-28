<template>
    <div>
        <div class="modal fade" id="modaladdsupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New Supplier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        


                        <form method="post" @submit.prevent="addsupplier">
                            
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter Supplier Information</h3>
                            </div>
                            
                            <div class="md-form mb-2">


                               <label for="fullname">Fullname</label>
                                        <input name="fullname" type="text" id="fullname" class="form-control "
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


                            <span :class="MessageClass">{{Message}}</span>
                            
                            <hr>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary next-btn pull-right " value="Add Supplier">

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

        mounted() {

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
        methods: {
           
            untoggle_modal: function (id) {
                $("#" + id).modal('hide');
            },
            addsupplier: function () { //this method adds a supplier via the api 
              // console.log(this.supplier)
                
                axios.post('/api/suppliers',this.supplier)
                    .then((response)=>{
                        console.log(response)

                        switch (response.status) {
                            case 201:

                                this.Message = "Supplier was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.supplier = {}
                                $("#image").val("")
                                //emit back the created Supplier : 
                                
                                this.$emit('created', response.data.data)


                                
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
                            
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 422 :                              
                                this.Message  = Object.values(error.response.data.errors)[0][0]
                                
                                this.MessageClass = "text-danger"
                                return

                                
                                break;
                        
                            default:
                                break;
                            }   
                            this.Message = "Supplier Couldn't be Added : "+error.response.statusText
                        }else if (error.request) {
                            /*
                            * The request was made but no response was received, `error.request`
                            * is an instance of XMLHttpRequest in the browser and an instance
                            * of http.ClientRequest in Node.js
                            */
                            console.log(error.request);
                        } else {
                            // Something happened in setting up the request and triggered an Error
                            console.log('Error', error.message);
                        }
                       
                    });
            


            },


        },
        components: {

        },

    }

</script>


<style scoped>
    body {
        font-family: Roboto, sans-serif;

    }

    label {
        font-size: 14px;
        color: gray;
        margin-bottom: 0px;

    }

    .next-btn {
        width: 150px;

    }

</style>
