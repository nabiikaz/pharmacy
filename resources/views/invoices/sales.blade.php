<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>Sale Invoice</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        .text-right {
            text-align: right;
        }

    </style>

</head>

<body class="login-page" style="background: white;zoom:95%;">
    

        <div style="padding-right:20px;">
            <div class="row">
                <div class="col-xs-7">
                    
    
                    <strong>{{$store["store_name"]}}</strong><br>
                    {{$store["address1"]}} <br>
                    {{$store["address2"]}}<br>
                    Email: {{$store["store_email"]}} <br>
                    Tel: {{$store["tel"]}} <br>
                   

    
                    <br>
                </div>
    
                <div class="col-xs-4">
                    <img src="{{URL::asset('/img/icons/logo.png')}}" width="250" >
                </div>
            </div>
    
            <div style="margin-bottom: 0px">&nbsp;</div>
    
            <div class="row">
                <div class="col-xs-6 align-middle " style="margin-top:-25px;height:130px">
                    <h4>Customer :</h4>
                    <div class="">
                        
                        Name:<strong>{{$customer["name"]}}</strong><br>
                        @if (isset($customer["email"]))
                        Email :<span>{{$customer["email"]}}</span> <br>
                        @endif
    
                        @if (isset($customer["address"]))
                        Address :<span>{{$customer["address"]}}</span> <br>
                        @endif
    
                        @if (isset($customer["tel"]))
                        Tel :<span>{{$customer["tel"]}}</span> <br>
                        @endif

                        @if ($customer["refund_rate"] > 0)
                        Refund Rate :<span> {{$customer["refund_rate"]}}</span> %<br>
                        @endif
    
                    </div>
                </div>
    
                <div class="col-xs-5 " style="height:130px">
                    <table class="align-bottom" style="width: 100%">
                        <tbody>
                            @if(!$sale["delivery"])
                                <tr>
                                    <th>Pharmacist:</th>
                                    <td class="text-right">{{$pharmacist}}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Invoice Num:</th>
                                <td class="text-right">#{{$sale["id"]}}</td>
                            </tr>
                            <tr>
                                <th> Invoice Date: </th>
                                <td class="text-right text-warning">{{Carbon\Carbon::parse($sale["created_at"])->format("Y M d")}}</td>
                            </tr>
                        </tbody>
                    </table>
    
                    <div style="margin-bottom: 0px">&nbsp;</div>
    
                    <table style="width: 100%; margin-bottom: 20px">
                        <tbody>
                            <tr class="bg-primary" style="padding: 5px">
                                <th style="padding: 5px">
                                    <div> Balance Due  </div>
                                </th>
                                <td style="padding: 5px" class="text-right "><strong> {{$invoice["total"]}} DA </strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <table class="table">
                <thead style="background: #F5F5F5;">
                    <tr>
                        <th style="width:10px;">#</th>
                        <th style="width:40%">Medicines</th>

                        <th class="text-center">Family</th>
                        <th class="text-center">Form</th>
                        
                        <th>Dosage</th>
                        <th class="text-center" >Quantity</th>
                        <th class="text-right">Price (DA)</th>
                        @if ($customer["refund_rate"] > 0)
                        <th class="text-right">Refund (%)</th>
                        <th class="text-right">Total (DA)</th>
                        @endif
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batches as $index =>$batch)
                        <tr class="">
                            <td class="text-success">{{$index+1}}</td>

                            <td >
                               <span >{{$batch["name"]}}</span> 
                            </td>

                            <td class="text-center">
                                    {{$batch["family"]}}
                            </td>

                            <td class="text-center">
                                    {{$batch["form"]}}
                            </td>

                            <td >
                                <span class="">{{$batch["dosage"]}} mg</span>
                            </td>

                            <td class="text-center" style="width:40px" >
                                    {{$batch["quantity_sold"]}} 
                            </td>
                            <td class="text-right">{{$batch["unit_price"]}} DA</td>
                            @if ($customer["refund_rate"] > 0)
                            <td class="text-center {{ $batch["refund_rate"] == 0  ? 'bg-danger' : 'bg-success' }}">{{$batch["refund_rate"]}} </td>
                            <td class="text-center {{ $batch["refund_rate"] == 0  ? 'bg-danger' : 'bg-success' }}">
                                {{$batch["quantity_sold"] * ($batch["unit_price"]-($batch["unit_price"] * $batch["refund_rate"]/100)*$customer["refund_rate"]/100)}}
                             </td>
                            
                            @endif
                        </tr>
                    @endforeach
                   
                    
                    
                </tbody>
            </table>
    
            <div class="row">
                <div class="col-xs-6"></div>
                <div class="col-xs-5">
                    <table style="width: 100%">
                        <tbody>
                            <tr class="well" style="padding: 5px">
                                <th style="padding: 5px">
                                    <div> Sub-total </div>
                                </th>
                                <td style="padding: 5px" class="text-right"><strong> {{$invoice["sub_total"]}} DA </strong></td>
                            </tr>
                            @if ($sale["delivery"])
                                <tr class="bg-success" style="padding: 5px">
                                    <th style="padding: 5px">
                                        <div> Shipping </div>
                                    </th>
                                    <td style="padding: 5px" class="text-right"><strong> {{$invoice["shipping"]}} DA  </strong></td>
                                </tr>
                            @endif

                            
                            
                            <tr class="bg-danger" style="padding: 5px">
                                    <th style="padding: 5px">
                                        <div> Taxes </div>
                                    </th>
                                    <td style="padding: 5px" class="text-right"><strong> {{$invoice["tax"]}} DA </strong></td>
                            </tr>
                            <tr class=" bg-primary" style="padding: 5px">
                                    <th style="padding: 5px">
                                        <div> Balance Due </div>
                                    </th>
                                    <td style="padding: 5px" class="text-right"><strong> {{$invoice["total"]}} DA </strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <div style="margin-bottom: 0px">&nbsp;</div>
    
            <div class="row">
                    <div class="col-xs-8 invbody-terms" style="color:gray;">
                        <p><strong>PHARMA-SINA</strong>  Online Services </p>
                    </div>
                </div>        
        </div>
        
    
    

</body>

</html>
