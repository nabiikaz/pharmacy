<template>

    <div>
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">
               <div class="container text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                  Dashboard <span class="sr-only">(current)</span>
               </div>
            </a>
            <ul class="navbar-nav px-3 dropdown">
                <li class="nav-item text-nowrap ">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-tooltip="dropdown" aria-haspopup="true" aria-expanded="false">
                     
                            <img :src="'/img/avatars/'+current_user.img" class="avatar" alt="Avatar">
                       
                      <span>{{current_user.name}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pb-0" style="position:absolute;" aria-labelledby="dropdownMenuButton">
                      
                      <hr style="margin:0px;">
                      <a class="dropdown-item " href="/logout">Logout</a>

                  </div>
                </li>
                
                  
                  
                
            </ul>
      </nav>
    </div>


</template>

<script>
export default {
  props:["current_user"],
  mounted(){
    $('.dropdown-toggle').dropdown()
    

  }
    
}
</script>


<style scoped>

.navbar-brand {
  padding-top: .75rem;
  padding-bottom: .75rem;
  font-size: 1rem;
  background-color: rgba(0, 0, 0, .25);
  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .form-control {
  padding: .75rem 1rem;
  border-width: 0;
  border-radius: 0;
}

.form-control-dark {
  color: #fff;
  background-color: rgba(255, 255, 255, .1);
  border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
  color:rgba(255, 255, 255, .1);
  
  border-color: transparent;
  box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}
.avatar {
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
        width: 40px;
    }

</style>

