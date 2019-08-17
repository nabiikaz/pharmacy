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
                        


                        <form method="post" @submit.prevent="NextModal">
                            
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter customer Information</h3>
                            </div>
                            
                            <div class="md-form mb-2">


                               <label for="fullname">Fullname</label>
                                        <input name="fullname" type="text" id="fullname" class="form-control "
                                            v-model="fullname" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                               <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="tel" required pattern="[0-9]{9}|[0-9]{10}">
                            </div>

                             <div class="md-form mb-2">


                               <label for="address">Address</label>
                                        <input name="address" type="text" id="address" class="form-control "
                                            v-model="address" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                              <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="email" required>
                            </div>


                            
                            <hr>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary next-btn pull-right " value="Add customer">

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

        props: ['customerId'],

        mounted() {

        },
        data() {
            return {

                img: "/img/icons/man.png",
                Img: null,
                fullname: "",
                address: "",
                tel: "",
                email: "",
                


               

            }
        },
        methods: {
            onFileSelected : function(event){
                this.img = URL.createObjectURL(event.target.files[0])

                this.Img = new FormData()
                this.Img.append("image",event.target.files[0],event.target.files[0].name)
            },

            NextModal: function (id) {
                
               //hide the current modal (the personal information modal)
                $("#modaladdcustomer").modal('hide')
                
                //show the second modal (the credentials modal)
                $("#modaladdcustomer2").modal("show")



                

            },
            PreviousModal : function(){
                
                

                //hide the current modal (the credentials modal)
                $("#modaladdcustomer2").modal('hide')
                
                //show the first modal (the personal information modal)
                $("#modaladdcustomer").modal("show")



                
            },
            untoggle_modal: function (id) {
                $("#" + id).modal('hide');
            },
            addcustomer: function () { //this method adds a customer via the api 
                /* we add new customer using Axios 
                *
                *
                * 
                * 
                * 
                * 
                * 
                * */


                alert("customer Added")
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
