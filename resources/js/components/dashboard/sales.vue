<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Medicines / sales </h2>
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
                   
                </div>

            </div>
            <div class="row">
                <div class="col-sm-5">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard/medicines">Medicines</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " href="/dashboard/medicines/batches">Batches</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="#">Sales</a>
                        </li>



                    </ul>

                </div>

                
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr style="cursor:pointer">







                       
                        <th  class="text-center" :class="(selected_column == 'pharmacist')? 'select-search':''"
                            @click="((selected_column == 'pharmacist'))? selected_column='':selected_column = 'pharmacist'">Made By</th>
                        
                        <th class="text-center" :class="(selected_column == 'customer')? 'select-search':''"
                            @click="((selected_column == 'customer'))? selected_column='':selected_column = 'customer'">Customer</th>
                        
                        
                        <th class="text-center" :class="(selected_column == 'created_at')? 'select-search':''"
                            @click="((selected_column == 'created_at'))? selected_column='':selected_column = 'created_at'">Date</th>
                       
                        <th class="text-center" :class="(selected_column == 'total_price')? 'select-search':''"
                            @click="((selected_column == 'total_price'))? selected_column='':selected_column = 'total_price'">Total Price
                        </th>
                        <th class="text-center" style="width:170px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(sale,index) in sales" :key="index" >



                       
                        <td class="text-center" :class="(selected_column == 'pharmacist')? 'select-search-data':''">
                            {{sale.pharmacist }}</td>

                        <td class="text-center" :class="(selected_column == 'customer')? 'select-search-data':''">
                            {{sale.customer }}</td>

                        <td class="text-center" :class="(selected_column == 'created_at')? 'select-search-data':''">
                            {{sale.created_at }}</td>
                       
                        <td class="text-center" :class="(selected_column == 'total_price')? 'select-search-data':''">
                            {{sale.total_price }}</td>



                        <td class="text-center">
                            <a href="#" class="invoice" title="" data-tooltip="tooltip" data-original-title="invoice"
                                data-toggle="modal" data-target="#modalinvoice" @click="selectedSale_Id=sale.id"><img
                                    src="/img/icons/bill.png" width="22"></a>

                           






                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete">
                                <img src="/img/icons/trash.png" width="24">
                            </a>
                        </td>

                    </tr>


                </tbody>
            </table>
            <div class="clearfix">

                <ul class="pagination">
                    <li class="page-item disabled"><a href="#"
                            @click="(paginationCurrent > 1)? paginationCurrent--:true; ">Previous</a></li>
                    <li class="page-item" v-if="paginationCurrent > 2"><a href="#" @click="paginationCurrent -=2 "
                            class="page-link">{{paginationCurrent -2}}</a></li>
                    <li class="page-item" v-if="paginationCurrent > 1"><a href="#" @click="paginationCurrent--"
                            class="page-link">{{paginationCurrent -1}}</a></li>
                    <li class="page-item active"><a href="#" class="page-link">{{paginationCurrent}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ "
                            class="page-link">{{paginationCurrent+1}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent+=2 "
                            class="page-link">{{paginationCurrent+2}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ " class="page-link">Next</a></li>
                </ul>
            </div>
        </div>

        <div id="modalinvoice" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content " style="border-top-left-radius:5px;border-top-right-radius:10px; ">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">View Invoice</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="/img/icons/load.gif" v-if="iframeStatus" style="position:absolute;margin-top:150px;margin-left:350px;">
                        <iframe :src="(selectedSale_Id > -1)? '/invoice/sales/'+selectedSale_Id+'?action='+invoiceAction:''"
                            frameborder="0" width="770" height="400" v-on:load="iframeStatus=false"></iframe>
                    </div>

                    <div class="modal-footer">
                        <div class="row w-100">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" @click="(invoiceAction=='view')? invoiceAction='':invoiceAction='view';iframeStatus=true">View{{(invoiceAction=='view')? ' As PDF':' As Html Page'}}</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-success w-100" @click="invoiceAction='download'; iframeStatus=true">Download PDF</button>
                            </div>
                            
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</template>


<script>
    export default {

        mounted() {

             this.getsales();
            $('[data-tooltip="tooltip"]').tooltip();


            $('[data-toggle="popover"]').mouseover(function () {
                $(this).popover("show")


            })

            $('[data-toggle="popover"]').mouseout(function () {
                $(this).popover("hide")
            })





        },

        data() {

            return {
                iframeStatus:true,
                invoiceAction:"view",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                batchId: this.$attrs.batchId,
                route: window.location.pathname,
                search: "",

                selectedSale_Id: -1,

                selected_column: '',

                filter_flow: 'Ascending',
                

                //displayed sales 
                sales: [],


                paginationCurrent: 1

            }
        },

        watch: {
            paginationCurrent: function (page) {
                this.getsales();
            },
            search: function (val) {
                this.paginationCurrent = 1

                this.getsales()

            },
            selected_column: function(){
                this.getsales();
            },
            filter_flow : function(){
                 this.getsales();
            },


        },




        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
        methods: {
            //iframeLoaded fires when the iframe is fully loaded 
            iframeLoaded: function(){
                
            },
            //get Purchases in the current page 
            getsales: function(){
                /** get Purchases in the current page using the server's API with (axios)
                 * 
                 * 
                 * 
                 */
                axios.get("/api/sales", {
                    params:{
                            
                        search:this.search,
                        selected_column:this.selected_column,
                        filter_flow:this.filter_flow,
                        page:this.paginationCurrent
                        }
                    
                    })
                        .then((response) => {
                        this.sales = response.data.data;
                        
                    }, (error) => {
                        console.log(error);
                    });


                    

            

            },
           

        },
        components: {


        }

    }

</script>


<style scoped>
.popover{
    max-width: 100%; /* Max Width of the popover (depending on the container!) */
}
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

    label {
        font-size: 14px;
        color: gray;

    }

    .select-search-data {

        background-color: #c5e4b254;
    }

    .select-search {
        background-color: #4e9cda;

    }
    th{
        border-left:  1px solid  #dee2e6;
        border-right:  1px solid  #dee2e6;

    }

</style>
