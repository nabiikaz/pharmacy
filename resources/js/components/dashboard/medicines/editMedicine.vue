<template>
    <div>
        <div class="modal fade" id="modaleditMedicine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Edit Medicine</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                       <form method="post" @submit.prevent="editMedicine" id="editMedicineForm">
                            <div class="md-form mb-2 text-center">
                                <input style="display:none;" type="file" name="image" ref="imgInput" @change="onFileSelected"  >
                                <img class="shadow-sm p-3  bg-white rounded" v-bind:src="img" width="150"
                                    title="Upload the Medicine Image" style="cursor:pointer;"
                                    @click="$refs.imgInput.click()" />
                            </div>
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter New Medicine Information</h3>
                        </div>

                            <div class="md-form mb-2">


                                <div class="row">
                                    <div class="col-6">
                                        <label for="name">Name</label>
                                        <input name="medicine_name" type="text" id="name" class="form-control " v-model="medicine.medicine_name"
                                            required minlength="8" maxlength="25">
                                    </div>
                                    <div class="col-6">
                                        <label for="dosage">Dosage (mg)</label>
                                        <input name="dosage" type="number" id="dosage" class="form-control "
                                            v-model="medicine.dosage" required min="0.01" step="0.01">

                                    </div>
                                </div>
                            </div>



                            <div class="md-form mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="form">Form </label>
                                        <select name="form" id="form" class="form-control" v-model="medicine.form" required>
                                            <option value="Comprime">Comprimé</option>
                                            <option value="gelule">gélule</option>
                                            <option value="collyre">collyre</option>
                                            <option value="sirop">sirop</option>
                                        </select>

                                    </div>
                                    <div class="col-6">
                                        <label for="Family">Family</label>

                                        <input name="family" type="text" id="Family" class="form-control "
                                            v-model="medicine.family" required>

                                    </div>
                                </div>


                            </div>

                            <span :class="MessageClass">{{Message}}</span>
                           

                            <hr>
                            <div class="d-flex justify-content-center ">
                                <input type="submit" class="btn btn-primary next-btn w-100 " value="Edit" :disabled="disable">

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
        props: ['medicineId'],


        watch: {
            medicineId: function (Id) {
                
                this.getMedicine(Id)
                this.Message = ""
                this.MessageClass = "text-success"
            },
           

        },
        data() {
            return {
                disable:false,//this boolean variable is for disabling the inputs if an error occures
                Message:"",
                MessageClass:"text-success",
                img: "/img/icons/pills.png",
                ImgFormData:null,
                medicine:{

                },





            } 


        },

        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
methods: {
             onFileSelected : function(e){
                this.img = URL.createObjectURL(e.target.files[0])

                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = (e) => {
                    this.medicine.image = e.target.result
                }

                //console.log(this.medicine)

                
                

                

                
            },
            getMedicine: function(medicineId){
                    this.disable = false
                    this.errorMsg = ""
               
                    axios.get('/api/medicines/'+this.medicineId)
                    .then((Response)=>{
                         this.medicine = Response.data.data
                         this.img = "/img/medicines/"+this.medicine.Img
                         console.log(this.medicine)

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            this.Message = "Medicine : "+error.response.statusText
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                    })
               
               

                 
            },

            editMedicine: function (event) {
                /** edit the Medicine using Axios via the server's API
                 * 
                 * 
                 * 
                 * 
                 * 
                 */
                
                
              

                axios.patch('/api/medicines/'+this.medicineId,this.medicine)
                    .then((Response)=>{
                        this.ImgFormData = null
                        this.getMedicine(this.medicineId)
                                console.log("here")
                        switch (Response.status) {
                            case 200:

                                this.Message = "Medicine was updated successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.medicine = {}
                                $("#image").val("")
                                this.img = "/img/icons/pills.png"
                                //emit back the edited Medicine : 
                                this.$emit('updated', Response.data.data)
                                console.log("updated")


                                
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
                            this.Message = "Medicine : "+error.response.statusText
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
