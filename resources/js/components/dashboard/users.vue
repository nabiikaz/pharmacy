<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Users <b>Manager</b></h2>
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
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modaladdUser" @click="getUsers()">
                            <i class="material-icons"></i> <span>Add New User</span>

                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr style="cursor:pointer">

                        <th :class="(selected_column == 'name')? 'select-search':''"
                            @click="((selected_column == 'name'))? selected_column='':selected_column = 'name' ">Full Name</th>
                        <th :class="(selected_column == 'birthday')? 'select-search':''"
                            @click="((selected_column == 'birthday'))? selected_column='':selected_column = 'birthday' ">Birthday</th>
                        <th :class="(selected_column == 'tel')? 'select-search':''"
                            @click="((selected_column == 'tel'))? selected_column='':selected_column = 'tel' ">Phone Num</th>
                        <th :class="(selected_column == 'email')? 'select-search':''"
                            @click="((selected_column == 'email'))? selected_column='':selected_column = 'email' ">email</th>
                        <th :class="(selected_column == 'role')? 'select-search':''"
                            @click="((selected_column == 'role'))? selected_column='':selected_column = 'role' ">Role</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :style="user.highlight" v-for="(user,index) in users" :key="index">

                        
                        <td  :class="(selected_column == 'fullname')? 'select-search-data':''">
                            <img v-bind:src="'/img/avatars/'+user.Img" class="avatar" alt="Avatar">
                            {{user.name}}
                        </td>
                        <td :class="(selected_column == 'birthday')? 'select-search-data':''">{{user.birthday}}</td>
                        <td :class="(selected_column == 'tel')? 'select-search-data':''">{{user.tel}}</td>
                        <td :class="(selected_column == 'email')? 'select-search-data':''">{{user.email}}</td>
                        <td :class="(selected_column == 'role')? 'select-search-data':''">{{user.role}}</td>
                        <td>
                           <a href="#" class="settings " title="" data-tooltip="tooltip" data-original-title="Settings"
                                v-on:click="settings(user.id)"><img src="/img/icons/settings.png" width="22" ></a>
                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete" @click="deleteUser(user.id)">
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

        <div class="alert alert-danger " role="alert">
            <strong>Error : </strong> {{ErrorMessage}}
             <button type="button" class="close" @click="hideAlert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            
        </div>


        <editUser :userId="selectedUser_Id" @updated="onUserUpdated" ></editUser>
        <addUser @created="onUserCreated"></addUser>


    </div>
</template>


<script>
    import editUser from './users/editUser.vue'
    import addUser from './users/addUser.vue'
    

    export default {
        mounted() {
            $(".alert").hide()
            $('[data-tooltip="tooltip"]').tooltip();
            this.getUsers()

        },

        data() {
            return {
                ErrorMessage:"",
                search:"",
                selectedUser_Id: -1,

                selected_column: '',

                filter_flow: 'Ascending',
                //displayed Users 
                users: [],
                paginationCurrent : 1

            }
        },
        watch:{
            paginationCurrent : function(val){
                    this.getUsers();
            },
             search : function(val){
            this.paginationCurrent = 1

                this.getUsers()

            } ,
            selected_column: function(){
                this.getUsers();
            },
            filter_flow : function(){
                 this.getUsers();
            },
            selectedUser_Id: function(old_val,new_val){
                this.getUsers();
            }
        },
        



        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
methods: {
            hideAlert: function(){
                $('.alert').hide()
            },
            //when a user is updated event is triggered from the editMedicine Child component            
            onUserUpdated : function(user){
                
                user.highlight = "background-color:#2ec741"     
                
                
                
                $("#modaleditMedicine").modal("hide") // we first need to hide the "modaladdMedicine" modal
                //we add the update the  user with the selectedMedicine_Id

                for (let index = 0; index < this.users.length; index++) {
                    if(this.users[index].id == this.selectedUser_Id){
                                          
                        this.users.splice(index,1)
                        this.users.unshift(user)
                        console.log("here")


                        

                    }
                    
                }
                


            },
            //when a user is created event is triggered from the addMedicine Child component
            onUserCreated: function(user){
                user.highlight = "background-color:#2ec741"     

                $("#modaladdMedicine").modal("hide") // we first need to hide the "modaladdMedicine" modal
                //we add the new user in the start
                this.users.unshift(user)
                
            },
              //delete users 
            deleteUser: function(userId){
                 axios.delete('/api/pharmacists/'+userId).then((response)=>{
                                this.getUsers()

                 })
                    .catch(error => {
                        
                        switch (error.response.status) {
                            case 403:
                                this.ErrorMessage = error.response.statusText + " , " + error.response.data.message
                                $(".alert").show();
                                
                                break;
                            case 404:

                                //if the user is already deleted then refresh current users view 
                                this.getUsers()

                                
                                break;
                        
                            default:
                                break;
                        }
                    });

            },
            //get users in the current page 
            getUsers: function(){
                /** get users in the current page using the server's API with (axios)
                 * 
                 * 
                 * 
                 */
                axios.get("/api/pharmacists", {
                    params:{
                            
                        search:this.search,
                        selected_column:this.selected_column,
                        filter_flow:this.filter_flow,
                        page:this.paginationCurrent
                        }
                    
                    })
                        .then((response) => {
                        this.users = response.data.data;
                        
                    }, (error) => {
                        console.log(error);
                    });


                    

            

            },
            settings(UserId) {
                this.selectedUser_Id = UserId

                $("#modaleditUser").modal("show")
            },
          
        },
        components: {
            'editUser': editUser,
            'addUser': addUser,
            
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
    th{
        border-left:  1px solid  #dee2e6;
        border-right:  1px solid  #dee2e6;

    }
    

</style>
