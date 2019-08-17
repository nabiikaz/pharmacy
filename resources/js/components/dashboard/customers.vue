<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Customers <b>Manager</b></h2>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control form-control-dark w-100" type="text" placeholder="Search"
                            aria-label="Search" v-model="search">

                    </div>
                    <div class="col-sm-1 ml-0 pl-0">
                        <img class="" style="cursor:pointer;color:white;" :src="'/img/icons/'+filter_flow+'.png'" width="38"
                                @click="(filter_flow == 'Ascending' )? filter_flow='Descending':filter_flow='Ascending'"
                                :title="filter_flow">
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modaladdcustomer">
                            <i class="material-icons"></i> <span>Add New customer</span>

                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr style="cursor:pointer">

                        <th :class="(selected_column.includes('fullname'))? 'select-search':''"
                            @click="((selected_column.includes('fullname'))? selected_column=selected_column.filter(function(e) { return e !== 'fullname' }):selected_column.push('fullname')) ">Full Name</th>
                       
                        <th :class="(selected_column.includes('address'))? 'select-search':''"
                            @click="((selected_column.includes('address'))? selected_column=selected_column.filter(function(e) { return e !== 'address' }):selected_column.push('address')) ">Address</th>
                       
                        <th :class="(selected_column.includes('tel'))? 'select-search':''"
                            @click="((selected_column.includes('tel'))? selected_column=selected_column.filter(function(e) { return e !== 'tel' }):selected_column.push('tel')) ">Phone Num</th>
                       
                        <th :class="(selected_column.includes('email'))? 'select-search':''"
                            @click="((selected_column.includes('email'))? selected_column=selected_column.filter(function(e) { return e !== 'email' }):selected_column.push('email')) ">email</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(customer,index) in customers" :key="index">

                        
                        <td  :class="(selected_column.includes('fullname'))? 'select-search-data':''">
                            {{customer.fullname}}
                        </td>
                        <td  :class="(selected_column.includes('address'))? 'select-search-data':''">{{customer.address}}</td>
                        <td  :class="(selected_column.includes('tel'))? 'select-search-data':''">{{customer.tel}}</td>
                        <td  :class="(selected_column.includes('email'))? 'select-search-data':''">{{customer.email}}</td>
                        <td  >
                           <a href="#" class="settings " title="" data-tooltip="tooltip" data-original-title="Settings"
                                v-on:click="settings(customer.Id)"><img src="/img/icons/edit.png" width="22" ></a>
                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete">
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


        <editCustomer  ></editcustomer>
        <addCustomer></addCustomer>


    </div>
</template>


<script>
    import editCustomer from './customers/editCustomer.vue'
    import addCustomer from './customers/addCustomer.vue'
    

    export default {
        mounted() {
            $('[data-tooltip="tooltip"]').tooltip();

        },

        data() {
            return {
                search:"",
                selectedcustomer_Id: -1,
                filter_flow:"Ascending",
                selected_column:[], 

                //displayed customers 
                customers: [{
                        Id: 1,
                        fullname: "nabi zakaria",
                        address: "bensekrane - tlemcen",
                        tel: "0555655100",
                        email: "nabi@gmail.com",
                    },
                    {
                        Id: 2,
                        fullname: "nabi zakaria",
                        address: "bensekrane - tlemcen",
                        tel: "0555655100",
                        email: "nabi@gmail.com",
                    },
                    {
                        Id: 3,
                        fullname: "nabi zakaria",
                        address: "bensekrane - tlemcen",
                        tel: "0555655100",
                        email: "nabi@gmail.com",
                    },

                    

                ],
                paginationCurrent : 1

            }
        },
        watch:{
            paginationCurrent : function(val){
                    this.getcustomers();
            },
             search : function(val){
            this.paginationCurrent = 1

                this.getcustomers()

            }
        },
        



        methods: {
                //get customers in the current page 
            getcustomers: function(){

                //don't forget the search and paginationCurrent 
                /** get customers in the current page using the server's API with (axios)
                 * 
                 * 
                 * 
                 */

            },
            settings(customerId) {
                this.selectedcustomer_Id = customerId

                $("#modaleditcustomer").modal("show")
            },
          
        },
        components: {
            'editCustomer': editCustomer,
            'addCustomer': addCustomer,
            
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

       .select-search-data {

        background-color: #c5e4b254;
    }

    .select-search {
        background-color: #4e9cda;

    }

</style>
