<template>
    <div>
        <div class="modal fade" id="modaladdMedicine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New Medicine</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        


                        <form  @submit.prevent="addMedicine" >
                            <div class="md-form mb-2 text-center">
                                <input style="display:none;" type="file" id="image" ref="imgInput" @change="onFileSelected" required >
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
                                        <input name="name" type="text" id="name" class="form-control " v-model="medicine.medicine_name"
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

                                        <input name="Family" type="text" id="Family" class="form-control "
                                            v-model="medicine.family" required>

                                    </div>
                                </div>


                            </div>
                            <span :class="MessageClass">{{Message}}</span>

                            

                            <hr>
                            
                            <div class="d-flex justify-content-center ">
                                <input type="submit" class="btn btn-primary next-btn w-100 " value="Add Medicine" >

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

        mounted() {
        },
        data() {
            return {

                img : "/img/icons/pills.png",
                Message:"",
                MessageClass:"text-success",

                
                medicine:{
                    
                
                medicine_name: "",
                dosage: "",
                form: "",
                family: "",
                }




            }
        },
        methods: {
            onFileSelected : function(e){
                this.img = URL.createObjectURL(e.target.files[0])

                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = (e) => {
                    this.medicine.image = e.target.result
                }

               // console.log(this.medicine)

                
                

                

                
            },



            addMedicine: function () { //this method adds a Medicine via the api 
                /* we add new Medicine using Axios 
                 *
                 *
                 * 
                 * 
                 * 
                 * 
                 * 
                 * */
                console.log(this.medicine)
                
                axios.post('/api/medicines',this.medicine)
                    .then((response)=>{
                        console.log(response)

                        switch (response.status) {
                            case 201:

                                this.Message = "Medicine was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.medicine = {}
                                $("#image").val("")
                                this.img = "/img/icons/pills.png"
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
                            this.Message = "Medicine Couldn't be created || Server Error : "+error.response.statusText
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
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
