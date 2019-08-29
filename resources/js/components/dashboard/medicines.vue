<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Medicines <b>Manager</b></h2>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control form-control-dark w-100" type="text" 
                            placeholder="Search" aria-label="Search" v-model="search">

                    </div>
                    <div class="col-sm-1  ml-0 pl-0">
                        <img class="" style="cursor:pointer;color:white;" :src="'/img/icons/'+filter_flow+'.png'" width="38"
                                @click="(filter_flow == 'Ascending' )? filter_flow='Descending':filter_flow='Ascending'"
                                :title="filter_flow">
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modaladdMedicine" @click="getMedicines()">
                            <i class="material-icons"></i> <span>Add New Medicine</span>

                        </a>
                    </div>
                </div>
                
            </div>
           <div class="row">
                    <div class="col-sm-5">
                         <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Medicines</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " href="/dashboard/medicines/batches">Batches</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " href="/dashboard/medicines/sales">Sales</a>
                            </li>

                        

                        </ul>
                        
                    </div>
                    <div class="col-sm-7 ">
                    <div class="row">
                        <div class="col-sm-1">
                            


                        </div>
                        <div class="col-sm-6">
                            <div class="md-form ">
                               

                            </div>

                        </div>
                        <div class="col-sm-5 d-flex justify-content-end  pr-5" data-toggle="modal" data-target="#modalCart">
                            <img style="cursor:pointer;" src="/img/icons/batch.png" width="38"
                                title="New Medicine Batches">
                            <span
                                :class=" (batches_in_cart_lenght==0)? 'rounded-circle   text-center pt-1  text-white bg-danger':'rounded-circle   text-center pt-1  text-white bg-success'"
                                style="width:30px;height:30px;position:absolute;margin-top:-13px;margin-left:13px;"><b>{{batches_in_cart_lenght}}</b></span>

                        </div>




                    </div>

                </div>

                  
                </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr style="cursor:pointer">
                        <th class="text-center " :class="(selected_column == 'medicine_name')? 'select-search':''"
                            @click="(selected_column == 'medicine_name')? selected_column='':selected_column = 'medicine_name'">Name</th>

                        <th class="text-center " :class="(selected_column == 'dosage')? 'select-search':''"
                            @click="(selected_column == 'dosage')? selected_column='':selected_column = 'dosage'">Dosage</th>
                        
                        <th class="text-center " :class="(selected_column == 'form')? 'select-search':''"
                            @click="(selected_column == 'form')? selected_column='':selected_column = 'form'">Form</th>
                        
                        <th class="text-center " :class="(selected_column == 'family')? 'select-search':''"
                            @click="(selected_column == 'family')? selected_column='':selected_column = 'family'">Family</th>
                            

                      
                        <th class="text-center " style="width:170px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :style="medicine.highlight" v-for="(medicine,index) in medicines" :key="index">

                        
                        
                        <td class="text-center " :class="(selected_column == 'medicine_name')? 'select-search-data':''"  >{{medicine.medicine_name}}</td>
                        <td class="text-center"  :class="(selected_column == 'dosage')? 'select-search-data':''">{{medicine.dosage}}</td>
                        <td class="text-center"  :class="(selected_column == 'form')? 'select-search-data':''">{{medicine.form}}</td>
                        <td class="text-center"  :class="(selected_column == 'family')? 'select-search-data':''">{{medicine.family}}</td>
                        
                       
                        
                        <td class="text-center">
                            <a href="#" class="batch" title="" data-tooltip="tooltip" data-original-title="Add New Batch" @click="openNewBatchModal(medicine.id,medicine.medicine_name)"
                            data-toggle="modal" data-target="#modalAddNewBatch"><img src="/img/icons/batch.png" width="22" ></a>
                            
                            <a href="#" class="edit" title="" data-tooltip="tooltip" data-original-title="Edit Medicine"
                                v-on:click="settings(medicine.id)"><img src="/img/icons/edit.png" width="22" ></a>
                            


                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete" @click="deleteMedicine(medicine.id)">
                                <img src="/img/icons/trash.png" width="24" ></a>
                        </td>

                    </tr>


                </tbody>
            </table>
            <div class="clearfix">

                <ul class="pagination">
                    <li class="page-item disabled"><a href="#"  @click="(paginationCurrent > 1)? paginationCurrent--:true; " >Previous</a></li>
                    <li class="page-item" v-if="paginationCurrent > 2"><a href="#" @click="paginationCurrent -=2 " class="page-link">{{paginationCurrent -2}}</a></li>
                    <li class="page-item" v-if="paginationCurrent > 1"><a href="#" @click="paginationCurrent--" class="page-link">{{paginationCurrent -1}}</a></li>
                    <li class="page-item active"><a href="#" class="page-link">{{paginationCurrent}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ " class="page-link">{{paginationCurrent+1}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent+=2 " class="page-link">{{paginationCurrent+2}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ " class="page-link">Next</a></li>
                </ul>
            </div>
        </div>


         <div id="modalCart" class="modal fade" role="dialog" >
            <div class="modal-dialog modal-lg" role="document" style="max-width:1300px;">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">New Medicine Batches</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="cursor:pointer">







                                    <th class="text-center ">Name</th>
                                    <th class="text-center">Fabrication Date</th>
                                    <th class="text-center">Expiry Date</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Batch Price</th>
                                    <th class="text-center">Quantity Purchased</th>
                                    <th class="text-center">Quantity Minimum</th>
                                    <th class="text-center">Refund Rate</th>
                                    <th class="text-center" style="with:1">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(batch,index) in batches_in_cart" :key="index">



                                    <td  class="text-center d-inline-block text-truncate" style="max-width: 195px;" >{{batch.medicine_name }}</td>
                                    <td class="text-center" >
                                        <input type="date"  style="width:155px;" class="form-control" v-model="batch.fabrication_date">
                                    </td>
                                    <td class="text-center" >
                                        <input type="date"  style="width:155px;" class="form-control" v-model="batch.expiry_date">
                                    </td>
                                    <td class="text-center">
                                        <input type="number"   class="form-control" v-model="batch.unit_price" required min="0" >
                                    </td>

                                     <td class="text-center">
                                        <input type="number"   class="form-control" v-model="batch.batch_price" required min="0" >
                                    </td>

                                   

                                    <td class="text-center">
                                        <input type="number" style="width:80px;" class="form-control" v-model.number="batch.quantity"  min="1">
                                    </td>

                                     <td class="text-center">
                                        <input type="number" style="width:80px;" class="form-control" v-model.number="batch.quantity_min"  min="1">
                                    </td>

                                    <td class="text-center">
                                        <input type="number" style="width:80px;" class="form-control" v-model.number="batch.refund_rate"  min="0" max="100">
                                    </td>


                                    <td class="text-center">
                                        

                                       

                                        <a href="#" class="delete" title="" data-tooltip="tooltip"
                                            data-original-title="Delete" @click="removeBatchFromCart(batch.Id)">
                                            <img src="/img/icons/trash.png" width="24">
                                        </a>
                                    </td>

                                </tr>


                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-10"></div>
                            <div class="col-2 d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary "  v-model="submitBatchesBtnTxt" @click="submitBatches">
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAddNewBatch" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Add New Medicine Batch</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">

                        <div class="md-form ">


                            
                            <form @submit.prevent="addNewBatch">
                                <span style="color:gray;">Medicine Name :<strong class="text-success"> {{batch_medicine_name}} </strong> </span>
                                <div class="pb-2 mt-3">
                                    <label for="fabrication_date" class="mb-0 ">Fabricaion Date</label>
                                    <input type="date" id="fabrication_date" class="form-control"
                                        v-model="newBatch.fabrication_date" required>
                                </div>

                                <div class="pb-2 ">
                                    <label for="expiry_date" class="mb-0 ">Expiry Datee</label>
                                    <input type="date" id="expiry_date" class="form-control"
                                        v-model="newBatch.expiry_date" required>
                                </div>
                                <div class="row">
                                   

                                    <div class="col-sm-6">
                                        <label for="quantity" class="mb-0 ">Quantity Purchased</label>
                                        <input type="number" id="quantity" class="form-control"
                                            v-model="newBatch.quantity" required min="0">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="quantity_minimum" class="mb-0 ">Quantity Minimum</label>
                                        <input type="number" id="quantity_minimum" class="form-control"
                                            v-model="newBatch.quantity_min" required min="0">
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="unit_price" class="mb-0 ">Unit Price</label>
                                        <input type="number" step="0.01" id="unit_price" class="form-control"
                                            v-model="newBatch.unit_price" required min="0">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="batch_price" class="mb-0 ">Batch Price</label>
                                        <input type="number" step="0.01" id="batch_price" class="form-control"
                                            v-model="newBatch.batch_price" required min="0">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="batch_price" class="mb-0 ">Refund Rate</label>
                                        <input type="number" step="0.01" id="batch_price" class="form-control" value="0"
                                            v-model="newBatch.refund_rate" required min="0" max="100">
                                    </div>

                                </div>

                                



                                <hr>

                                <div class="md-form ">
                                    <button class="form-control btn btn-success">Add Batch</button>
                                </div>

                            </form>










                        </div>





                    </div>


                </div>
            </div>
        </div>

           <div class="modal fade" id="modalSelectSupplier" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Add New Medicine Batch</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">

                        <div class="md-form ">
                            <h3 style="color: #6f6e6e;" class="text-center ">2- Select the Supplier : </h3>

                            <select v-model="selected_supplier_id" class="form-control">
                                <option v-for="(supplier,index) in suppliers" :key="index" :value="supplier.id">
                                    {{supplier.supplier_name}}</option>
                            </select>
                            <span class="text-primary mb-3">Or add a new supplier !</span>
                            <form @submit.prevent="addNewSupplier">
                                <div class="pb-2 ">
                                    <input type="text" placeholder="Fullname" class="form-control"
                                        v-model="new_supplier.supplier_name" required>
                                </div>

                                <div class="pb-2">
                                    <input type="tel" placeholder="phone number" class="form-control"
                                        v-model="new_supplier.tel" required minlength="9" maxlength="10">
                                </div>

                                <div class="pb-2">
                                    <input type="text" placeholder="address" class="form-control"
                                        v-model="new_supplier.address" required minlength="8" maxlength="35">
                                </div>

                                <div class="pb-2">
                                    <input type="email" placeholder="email" class="form-control"
                                        v-model="new_supplier.email" required>
                                </div>
                                <span :class="MessageClass">{{Message}}</span>

                                <hr>

                                <div class="md-form ">
                                    <button class="form-control btn btn-success">Add New Supplier</button>
                                </div>

                            </form>










                        </div>





                    </div>


                </div>
            </div>
        </div>



        
        
    <addMedicine @created="onMedicineCreated"></addMedicine>
   
   
    <editMedicine :medicineId="selectedMedicine_Id" @updated="onMedicineUpdated"> </editMedicine>

    </div>
</template>


<script>
  
    import editMedicine from './medicines/editMedicine.vue'
    
    import addMedicine from './medicines/addMedicine.vue'
    

    export default {
         components: {
            
            'addMedicine': addMedicine,
            'editMedicine' : editMedicine,
            
        },
        mounted() {
            this.getMedicines();
            $('[data-tooltip="tooltip"]').tooltip();

            



        },

        data() {
            return {
                 Message:"",
                MessageClass:"text-success",

                submitBatchesBtnTxt:"Select Supplier" ,
                newBatch:{},
               
                batch_medicine_name:"N/A", //batch_medicine_name is the name of the medicine selected for creating a new batch of it
                batch_medicineId:-1,//batch_medicineId is the medicine id of the selected medicine to create a new batch of it 

                batches_in_cart_lenght:0,
                batches_in_cart:[],

                selected_supplier_id: -1,
                
                route : window.location.pathname,
                selectedMedicine_Id: -1,
                
                new_supplier:{},

                suppliers:[],
               
                search : "",
                selected_column: '',
                filter_flow : 'Ascending',


                //displayed Users 
                medicines: [],
                paginationCurrent : 1

            }
        },
        watch:{
            selected_supplier_id: function(id){
                if(id > -1){
                    this.submitBatchesBtnTxt = "Submit Batches"
                    $("#modalSelectSupplier").modal("hide");
                    $("#modalCart").modal("show");
                }

            },
            paginationCurrent : function(page){
                    this.getMedicines();
            },
            search : function(val){
            this.paginationCurrent = 1

                this.getMedicines()

            },
            selected_column: function(){
                this.getMedicines();
            },
            filter_flow : function(){
                 this.getMedicines();
            },
            selectedMedicine_Id: function(old_val,new_val){
                this.getMedicines();
            },
            batches_in_cart: function(new_val){
                this.batches_in_cart_lenght = this.batches_in_cart.length;
            }
        },
        


        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 

        methods: {
            openNewBatchModal: function(medicineId,medicine_name){
                this.batch_medicine_name = medicine_name
                this.batch_medicineId = medicineId

                


            },
            //add  Batch to cart
            addNewBatch: function(){
                this.newBatch.medicine_name = this.batch_medicine_name
                this.newBatch.medicineId = this.batch_medicineId
                this.batches_in_cart.push(this.newBatch);
                this.newBatch = {};

                $("#modalAddNewBatch").modal("hide");











                this.batches_in_cart_lenght++;

            },
            //submit batches in cart to the server 
            submitBatches: function(){

                if(this.batches_in_cart_lenght <=  0){
                    alert("Please Add Medicines Batches To Continue....")
                    $("#modalCart").modal("hide")
                    return;
                }else if (this.selected_supplier_id == -1){
                    this.getSuppliers();
                    $("#modalCart").modal("hide")
                    $("#modalSelectSupplier").modal("show");
                }else {

                     axios.post('/api/batches',
                     {
                         "batches": this.batches_in_cart,
                         "supplier_id" : this.selected_supplier_id
                     })
                        .then((response)=>{
                            console.log(response)
                              this.Message = "Batches added successfully ."
                                    this.MessageClass = "text-success"
                                    //clear all inputs 
                                    this.batches_in_cart.splice(0,this.batches_in_cart.length)

                                    $("#modalCart").modal("hide")
                                    
                        
                            

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

                }


            },
            addNewSupplier:function(){
                console.log(this.new_supplier)
                
                axios.post('/api/suppliers',this.new_supplier)
                    .then((response)=>{

                        switch (response.status) {
                            case 201:

                                this.Message = "Supplier was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.supplier = {}
                                
                                
                                this.selected_supplier_id = response.data.data.id


                                
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

            //get Suppliers in the current page 
            getSuppliers: function(){
                /** get Suppliers in the current page using the server's API with (axios)
                 * 
                 * 
                 * 
                 */
                axios.get("/api/suppliers", {
                    params:{
                            
                        search:"",
                        selected_column:"",
                        filter_flow:"ascending",
                        page:1
                        }
                    
                    })
                        .then((response) => {
                        this.suppliers = response.data.data;
                        
                    }, (error) => {
                        console.log(error);
                    });


                    

            

            },

            //retrieve medicine form the database with the medicine Id 
             getMedicine: function(medicineId){
                    this.disable = false
                    this.errorMsg = ""
               
                    axios.get('/api/medicines/'+this.medicineId)
                    .then((Response)=>{
                         this.medicine = Response.data.data
                         

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                    })
               
               

                 
            },
           
            //remove batch from the cart 
            removeBatchFromCart: function(batchId){

                //search for the batch in batches_in_cart collection
                for (let i = 0; i < this.batches_in_cart.length; i++) {
                    if (batchId == this.batches_in_cart[i].Id){
                        this.batches_in_cart.splice(i,1)
                        this.batches_in_cart_lenght--
                    }
                    
                }

                

            },
            //when a medicine is updated event is triggered from the editMedicine Child component            
            onMedicineUpdated : function(medicine){
                
                medicine.highlight = "background-color:#2ec741"     
                

                $("#modaleditMedicine").modal("hide") // we first need to hide the "modaladdMedicine" modal
                //we add the update the  medicine with the selectedMedicine_Id

                for (let index = 0; index < this.medicines.length; index++) {
                    if(this.medicines[index].id == this.selectedMedicine_Id){
                                          
                        this.medicines.splice(index,1)
                        this.medicines.unshift(medicine)

                        

                    }
                    
                }
                


            },
            //when a medicine is created event is triggered from the addMedicine Child component
            onMedicineCreated: function(medicine){
                
                medicine.highlight = "background-color:#2ec741"     

                $("#modaladdMedicine").modal("hide") // we first need to hide the "modaladdMedicine" modal
                //we add the new medicine in the start
                this.medicines.unshift(medicine)
                
            },
            //delete medicines 
            deleteMedicine: function(medicineId){
                 axios.delete('/api/medicines/'+medicineId).then((response)=>{
                                this.getMedicines()

                 })
                    .catch(error => {
                        
                        switch (error.response.status) {
                            case 404:

                                //if the medicine is already deleted then refresh current medicines view 
                                this.getMedicines()

                                
                                break;
                        
                            default:
                                break;
                        }
                    });

            },
            //get users in the current page 
        getMedicines: function(){
            /** get users in the current page using the server's API with (axios)
             * 
             * 
             * 
             */
            axios.get("/api/medicines", {
                params:{
                        
                    search:this.search,
                    selected_column:this.selected_column,
                    filter_flow:this.filter_flow,
                    page:this.paginationCurrent
                    }
                   
                })
                    .then((response) => {
                    this.medicines = response.data.data;
                    
                }, (error) => {
                    console.log(error);
                });

           

        },
            settings: function(medicineId) {
                this.selectedMedicine_Id = medicineId

                $("#modaleditMedicine").modal("show")
            },
          
        },
       

    }

</script>


<style scoped>
    body {
        color: #566787;
        background: #f5f5f5;
        font-family: 'Varela Round', sans-serif;
        font-size: 13px;
    }

    .table-wrapper {
        background: #fff;
        padding: 20px 25px;
        margin: 30px 0;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title {
        padding-bottom: 15px;
        background: #299be4;
        color: #fff;
        padding: 16px 30px;
        margin: -20px -25px 10px;
        border-radius: 3px 3px 0 0;
    }

    .table-title h2 {
        margin: 5px 0 0;
        font-size: 24px;
    }

    .table-title .btn {
        color: #566787;
        float: right;
        font-size: 13px;
        background: #fff;
        border: none;
        min-width: 50px;
        border-radius: 2px;
        border: none;
        outline: none !important;
        margin-left: 10px;
    }

    .table-title .btn:hover,
    .table-title .btn:focus {
        color: #566787;
        background: #f2f2f2;
    }

    .table-title .btn i {
        float: left;
        font-size: 21px;
        margin-right: 5px;
    }

    .table-title .btn span {
        float: left;
        margin-top: 2px;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }

    table.table tr th:last-child {
        width: 100px;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
    }

    table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table td:last-child i {
        opacity: 0.9;
        font-size: 22px;
        margin: 0 5px;
    }

    table.table td a {
        font-weight: bold;
        color: #566787;
        display: inline-block;
        text-decoration: none;
    }

    table.table td a:hover {
        color: #2196F3;
    }

    table.table td a.settings {
        color: #2196F3;
    }

    table.table td a.delete {
        color: #F44336;
    }

    table.table td i {
        font-size: 19px;
    }

    table.table .avatar {
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
        width: 40px;
    }

    .status {
        font-size: 30px;
        margin: 2px 2px 0 0;
        display: inline-block;
        vertical-align: middle;
        line-height: 2px;
    }

    .text-success {
        color: #10c469;
    }

    .text-info {
        color: #62c9e8;
    }

    .text-warning {
        color: #FFC107;
    }

    .text-danger {
        color: #ff5b5b;
    }

    .pagination {
        float: right;
        margin: 0 0 5px;
    }

    .pagination li a {
        border: none;
        font-size: 13px;
        min-width: 30px;
        min-height: 30px;
        color: #999;
        margin: 0 2px;
        line-height: 30px;
        border-radius: 2px !important;
        text-align: center;
        padding: 0 6px;
    }

    .pagination li a:hover {
        color: #666;
    }

    .pagination li.active a,
    .pagination li.active a.page-link {
        background: #03A9F4;
    }

    .pagination li.active a:hover {
        background: #0397d6;
    }

    .pagination li.disabled i {
        color: #ccc;
    }

    .pagination li i {
        font-size: 16px;
        padding-top: 6px
    }

    .hint-text {
        float: left;
        margin-top: 10px;
        font-size: 13px;
    }
    .select-search-data{
        
        background-color: #c5e4b254;
    }
    .select-search{
        background-color: #4e9cda;

    }
    th{
        border-left:  1px solid  #dee2e6;
        border-right:  1px solid  #dee2e6;

    }

label {
        font-size: 14px;
        color: gray;

    }
</style>
