<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-5">
                        <h2>Users <b>Manager</b></h2>
                    </div>
                    <div class="col-md-7">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modaladdUser">
                            <i class="material-icons"></i> <span>Add New User</span>

                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>

                        <th>Full Name</th>
                        <th>Birthday</th>
                        <th>Phone Num</th>
                        <th>email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user,index) in users" :key="index">

                        
                        <td>
                            <img v-bind:src="user.avatarPath" class="avatar" alt="Avatar">
                            {{user.fullname}}
                        </td>
                        <td>{{user.birthday}}</td>
                        <td>{{user.tel}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.role}}</td>
                        <td>
                            <a href="#" class="settings" title="" data-tooltip="tooltip" data-original-title="Settings"
                                v-on:click="settings(user.userId)"><i class="material-icons"></i></a>
                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete"><i
                                    class="material-icons"></i></a>
                        </td>

                    </tr>


                </tbody>
            </table>
            <div class="clearfix">

                <ul class="pagination">
                    <li class="page-item disabled"><a href="#">Previous</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item active"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link">Next</a></li>
                </ul>
            </div>
        </div>


        <editUser :userId="selectedUser_Id" ></editUser>
        <addUser></addUser>


    </div>
</template>


<script>
    import editUser from './users/editUser.vue'
    import addUser from './users/addUser.vue'
    

    export default {
        mounted() {
            $('[data-tooltip="tooltip"]').tooltip();

        },

        data() {
            return {
                selectedUser_Id: -1,
                //displayed Users 
                users: [{
                        userId: 1,
                        avatarPath: "/img/avatars/nabi.jpg",
                        fullname: "nabi zakaria",
                        birthday: "07/29/1995",
                        tel: "0555655100",
                        email: "nabi@gmail.com",
                        role: "Admin"
                    },
                    {
                        userId: 2,
                        avatarPath: "/img/avatars/nabi.jpg",
                        fullname: "zakaria",
                        birthday: "07/29/1995",
                        tel: "0555655100",
                        email: "nabi@g.com",
                        role: "employee"
                    },
                    {
                        userId: 3,
                        avatarPath: "/img/avatars/nabi.jpg",
                        fullname: "nabi zakaria",
                        birthday: "07/29/1995",
                        tel: "0555655100",
                        email: "nabi@gmail.com",
                        role: "Admin"
                    },

                ],

            }
        },



        methods: {
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

</style>
