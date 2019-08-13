<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-5">
                        <h2>Medicines <b>Manager</b></h2>
                    </div>
                    <div class="col-md-7">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modaladdMedicine">
                            <i class="material-icons"></i> <span>Add New Medicine</span>

                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>

                        <th class="text-center">Name</th>
                        <th class="text-center">Dosage</th>
                        <th class="text-center">Form</th>
                        <th class="text-center">Family</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Refund</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(medicine,index) in medicines" :key="index">

                        
                        
                        <td class="text-center">{{medicine.name}}</td>
                        <td class="text-center">{{medicine.dosage}}</td>
                        <td class="text-center">{{medicine.form}}</td>
                        <td class="text-center">{{medicine.family}}</td>
                        <td class="text-center">{{medicine.stock}}</td>
                        <td class="d-flex justify-content-center">
                            <img width="22" height="22" v-bind:src="(medicine.refund > 0)? '/img/icons/checkmark.png':'/img/icons/xmark.png'" class="icon" >
                        </td>
                        
                        <td>
                            
                            <a href="#" class="settings" title="" data-tooltip="tooltip" data-original-title="Settings"
                                v-on:click="settings(medicine.Id)"><i class="material-icons"></i></a>
                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete"><i
                                    class="material-icons"></i></a>
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


        
        
    <addMedicine ></addMedicine>
    <editMedicine :medicineId="selectedMedicine_Id"> </editMedicine>

    </div>
</template>


<script>
    
    import addMedicine from './medicines/addMedicine.vue'
    import editMedicine from './medicines/editMedicine.vue'
    

    export default {
        mounted() {
            $('[data-tooltip="tooltip"]').tooltip();

        },

        data() {
            return {
                selectedMedicine_Id: -1,
                //displayed Users 
                medicines: [{
                    Id: 1,
                    name : "DOLIPRANE",
                    dosage : "500 mg",
                    form : "gélule",
                    family : "Antalgique et antipyrétique ",
                    stock : 100,
                    stock_min : 15,
                    refund: 1,//pourcentage of refund if 0% then no refund else a pourcentage is defined as a refund %
                    
                    },
                   

                ],
                paginationCurrent : 2

            }
        },
        watch:{
            paginationCurrent : function(val){
                    getMedicines(page);
            }
        },
        //get users in the current page 
        getMedicines: function(page){
            /** get users in the current page using the server's API with (axios)
             * 
             * 
             * 
             */

        },



        methods: {
            settings(medicineId) {
                this.selectedMedicine_Id = medicineId

                $("#modaleditMedicine").modal("show")
            },
          
        },
        components: {
            
            'addMedicine': addMedicine,
            'editMedicine' : editMedicine,
            
        }

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

</style>
