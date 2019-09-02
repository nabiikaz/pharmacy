<template>
    <div class="container">
        <div class="row pt-4 pb-4   ">
            <div class="col-3 site-logo">
                <a href="/" class="text-"> PHARMA-SINA </a>
            </div>
            <div class="col-6 pt-2 d-flex justify-content-center">
                <ul >
                    <li class="navbar-item active">
                        <a href="/">home</a>
                    </li>

                     <li class="navbar-item ">
                        <a href="#">services</a>
                    </li>

                     <li class="navbar-item ">
                        <a href="/about">about</a>
                    </li>

                     <li class="navbar-item ">
                        <a href="/contact">contact</a>
                    </li>

                   
                </ul>
                
            </div>


            <div class="col-3 site-logo d-flex justify-content-end">
                <div >

                    <img src="/img/icons/login.png" width="30" data-tooltip="tooltip" data-title="Login / Register">
                </div>
                <div class="ml-2">

                    <img src="/img/icons/add_to_cart.png" width="30" data-tooltip="tooltip" data-title="Click To View Items In Cart">
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
.navbar-item {
        padding: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #25262a;
    font-size: 15px;
    display: inline;
    text-decoration: none!important;
}

.navbar-item>a{
     color: #25262a;
     text-decoration-line: none;
}

.navbar-item.active{
    border-bottom: 2px solid;
    font-weight: 600;

}
.site-logo a {
    text-transform: uppercase;
    letter-spacing: .2em;
    font-size: 22px;
    color: #000!important;
    font-weight: 900
}

.site-logo a:hover {
    text-decoration: none
}
</style>