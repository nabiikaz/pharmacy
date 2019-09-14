<template>
    <div>
        <div class="modal fade" id="modaleditcustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Edit customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <form action="post" @submit.prevent="editCustomer">
                           <div class="md-form mb-2">


                               <label for="name">Fullname</label>
                                        <input name="name" type="text" id="name" class="form-control "
                                            v-model="customer.name" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                               <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="customer.tel" required pattern="[0-9]{9}|[0-9]{10}">
                            </div>

                             <div class="md-form mb-2">


                               <label for="address">Address</label>
                                        <input name="address" type="text" id="address" class="form-control "
                                            v-model="customer.address" required minlength="8" maxlength="25">
                                        <div class="row pl-2" @click="showMap" style="cursor:pointer">
                                            <img src="/img/icons/marker.png" width="30" height="30" data-tooltip="tooltip" data-title="Click Here To Point the Geo Coordinates" >
                                            <span style="font-size:13px;color:gray;padding-top:15px;" v-html="prettyGeoCoordinates"></span>

                                        </div>
                            </div>

                             <div class="md-form mb-2">


                              <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="customer.email" required>
                            </div>



                            <hr>

                            <span :class="MessageClass">{{Message}}</span>

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

        <geoCoordinates :modalId="geoModal" :focusMark="true" :coord_input="customer.geo_coord" @coordUpdate="coordinatesUpdated"></geoCoordinates>

    </div>
</template>


<script>
    export default {
        props: ['customerId'],


        watch: {
            customerId: function (Id) {
                this.getCustomer(Id)
                this.Message = ""
                this.MessageClass = "text-success"

            }

        },
        data() {
            return {
                geoModal:"geoModalEdit",
                Message:"",
                MessageClass:"text-success",

                customer:{},





            }


        },
        computed:{
            prettyGeoCoordinates: function(){
                let lat=0,lng=0
                if(this.customer.geo_coord == undefined)
                    return "No Geo Coordinates Has Been Defined For This Customer"


                if(RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(this.customer.geo_coord)){
                    lat = this.customer.geo_coord.split(",")[0]
                    lng = this.customer.geo_coord.split(",")[1]
                }else{
                    return "No Geo Coordinates Has Been Defined For This Customer"
                }

                let result = ""
                if(lat >=0)
                    result = lat+"<strong class='text-primary'> N </strong>"
                else
                    result = Math.abs(lat) + "<strong class='text-danger'> S </strong>"

                if(lng >=0)
                    result += lng+"<strong class='text-primary'> E </strong>"
                else
                    result += Math.abs(lng) + "<strong class='text-danger'> W </strong>"

                return result;

            }
        },

        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
        methods: {
            coordinatesUpdated: function(e){
                if(RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(e)){
                    this.customer.geo_coord = e
                }
            },
            showMap: function(){
                $("#"+this.geoModal).modal("show")

            },
            getCustomer: function(customerId){
                    this.disable = false
                    this.errorMsg = ""
               
                    axios.get('/api/customers/'+this.customerId)
                    .then((Response)=>{
                         this.customer = Response.data.data
                        

                         

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
                            this.Message = "customer : "+error.response.statusText
                        }
                    })
               
               

                 
            },

            editCustomer: function (event) {
                /** edit the Medicine using Axios via the server's API
                 * 
                 * 
                 * 
                 * 
                 * 
                 */
                
                

                axios.patch('/api/customers/'+this.customerId,this.customer)
                    .then((Response)=>{
                        this.ImgFormData = null
                        this.getCustomer(this.customerId)
                        switch (Response.status) {
                            case 200:


                                this.Message = "customer was updated successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.customer = {}
                                
                                //emit back the edited Customer : 
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
