<template>
    <div>
            <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body mx-3">
            
            <div class="md-form mb-2">
            
                <div class="row">
                    <div class="col-6">
                        <label for="fullname" >Fullname</label>
                        <input type="text" id="fullname" class="form-control validate" >
                    </div>
                    <div class="col-6">
                        <label for="fullname">Birthday</label>
                         <input type="date" id="birthday" class="form-control validate" placeholder="">
                    
                    </div>
                </div>
            </div>

            

            <div class="md-form mb-2">
            <div class="row">
                    <div class="col-6">
                        <label for="phone" >Phone Number</label>
                 <input type="tel" id="phone" class="form-control validate" placeholder="">
                        
                    </div>
                    <div class="col-6">
                        <label for="role">Role</label>
                        <select class="form-control" id="role">
                    
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
                    
                    </div>
                </div>
            
            </div>

            <div class="md-form mb-3">
                        <label for="email">Email</label>
                
                 <input type="email" id="email" class="form-control validate" placeholder="">
            
            </div>

          <hr>

            <div class="md-form mb-2">
                <div class="row">
                    <div class="col-6">
                        <label for="login" >Login Username</label>
                 <input type="text" id="login" class="form-control validate" placeholder="">
                       
                    </div>
                    <div class="col-6">
                        <label for="password">Password</label>
                 <input type="password" id="password" class="form-control validate" placeholder="">
                       
                    
                    </div>
                </div>
                 
            
            </div>
            <div class="md-form mb-2">
            
                 
            
            </div>
            
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-deep-orange">Sign up</button>
        </div>
        </div>
    </div>
    </div>

    </div>
</template>


<script>
export default {
    
}
</script>


<style scoped>

body{
        font-family: Roboto,sans-serif;

}

label{
    font-size: 14px;
    
}
</style>

