<template>
    <div>
        <div class="modal fade" id="modaladdcustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        


                        <form method="post" @submit.prevent="addCustomer">
                            
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter customer Information</h3>
                            </div>
                            
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

                                        <input name="email" type="email" id="email" class="form-control " required
                                        v-model="customer.email" >
                            </div>

                            <div class="md-form mb-2">


                                <label for="refund">Refund</label>

                                        <input name="refund" type="number" id="refund" class="form-control "
                                            v-model="customer.refund_rate" required step="0.01"   min="0" max="100">
                            </div>

                            <span :class="MessageClass">{{Message}}</span>

                            
                            <hr>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary next-btn pull-right " value="Add customer">

                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </div>

       <geoCoordinates :title="'Add Customer Coordinates'" :modalId="geoModal" :focusMark="true" :coord_input="customer.geo_coord" @coordUpdate="coordinatesUpdated"></geoCoordinates>


    </div>

</template>


<script>
    export default {

        props: ['customerId'],

        mounted() {

        },
        data() {
            return {
                geoModal:"geoModalCreate",

                  Message:"",
                MessageClass:"text-success",
                
                name: "",
                address: "",
                tel: "",
                email: "",

                customer:{
                    name:"",
                    tel:"",
                    address:"",
                    email:"",
                    geo_coord:"",
                    refund_rate:0
                },
                


               

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

                    
                    //we correct the address with the the nearest address to the geo coordinates
                    var platform = new H.service.Platform({
                        /*"app_id": "APP-ID-HERE",
                        "app_code": "APP-CODE-HERE"*/
                        apikey: 'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk'
                    });
                    var geocoder = platform.getGeocodingService();


                    geocoder.reverseGeocode({
                        language: "en",
                        mode: "retrieveAddresses",
                        maxresults: 1,
                        prox: e
                    }, data => {
                        let address = data.Response.View[0].Result[0].Location.Address;
                        
                        this.customer.address = address.State+", "+((address.County == address.City)? "":address.County)+" "+ address.Label;

                    }, error => {
                        console.error(error);
                    });

                    
                }
            },
            showMap: function(){
                $("#"+this.geoModal).modal("show")

            },
            untoggle_modal: function (id) {
                $("#" + id).modal('hide');
            },
            addCustomer: function () { //this method adds a customer via the api 
                console.log(this.customer)
                
                axios.post('/api/customers',this.customer)
                    .then((response)=>{
                        console.log(response)

                        switch (response.status) {
                            case 201:

                                this.Message = "Pharmacist was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.customer = {}
                                
                                //emit back the created Medicine : 
                                
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
                            this.Message = "Customer Couldn't be created || Server Error : "+error.response.statusText
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
