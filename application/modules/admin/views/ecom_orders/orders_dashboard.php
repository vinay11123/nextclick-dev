<!-- <h1>Orders dashboard</h1> -->

<style>
    .dropdown-menu.container.show {
    z-index: 999;
}
        div>.vendorbox {
            min-height: 50px;
            background-color: red;
            border-radius: 12px;
        }

        div>.vendorbox1 {

            background-color: red;
            border-radius: 12px;
            margin-top: 12px;
            margin-bottom:12px;
        }

        div>.vendorbox> {}
        .oitm,
        .oitu,
        .oitd,
        .oitt,
        .oitli{

        font-size:11px;
            margin-left: 10px;
        }
        div>span.fs,div>span.fss{
            font-size:10px !important;
        }
        .accept {
  color: #FFF;
  background: #44CC44;
  padding: 2px 4px;
                margin-left: 20px;
            text-decoration: none;
  box-shadow: 0 4px 0 0 #2EA62E;
}
.accept:hover {
  background: #6FE76F;
  box-shadow: 0 4px 0 0 #7ED37E;
}
.deny {
  color: #FFF;
  background: tomato;
  padding: 2px 4px;
  box-shadow: 0 4px 0 0 #CB4949;
        margin-left: 20px;text-decoration: none;
}
.deny:hover {
  background: rgb(255, 147, 128);
  box-shadow: 0 4px 0 0 #EF8282;
}

.pulse {
    animation-name: pulse;
    -webkit-animation-name: pulse;
    animation-duration: 1.5s; 
    -webkit-animation-duration: 1.5s;
    animation-iteration-count: infinite;
    -webkit-animation-iteration-count: infinite;

}      
    </style>

   

<div class="container ordvp">
       <!-- <div class="row">

            <div class="col-md-4 pt-4">
                <div class="vendorbox">
                    <h4 style="padding: 12px;">Vendor Orders</h4>

                </div>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">

            </div>
        </div>-->
        <div class="row">

            <div class="col-md-4">

                <label for="vendororders" class="form-group" style="color: orangered">
                    Vendor Orders

                </label>
                <select class="form-control" id="vendororders" name="sellist1">
                    <option value="Neworders">New Orders</option>
                    <option value="Pendingorders">Pending Orders</option>

                </select>

            </div>
            <div class="col-md-4">

                <label class="form-group" for="dateoforders" style="color: orangered">Date Of Orders</label>
                <input class="form-control" type="date" id="dateoforders" name="birthday">

             
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 style="padding: 12px;">Vendor Orders</h4>
            </div> 
           
            <div class="col-md-3">
                <div class="vendorbox1 pulse">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                 
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row showwithees">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                   <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
               <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
             
            <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color: darkmagenta">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                       <div class="oitli">
                       <label class="form-check-label" for="flexCheckChecked">
                        Checked Item 
                      </label>
                       </div>
                   </div>
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
             
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
               <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color:darkolivegreen">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
             
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
           
              <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color:darkorange">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
            
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
           
        </div>
         <!-- second -->
        
         <div class="row">
            <!-- <div class="col-md-12">
                <h4 style="padding: 12px;color:white;">Vendor Orders</h4>
            </div> -->
           
            <div class="col-md-3">
                <div class="vendorbox1 pulse">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                   <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
               <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
             
            <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color: darkmagenta">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                       <div class="oitli">
                       <label class="form-check-label" for="flexCheckChecked">
                        Checked Item 
                      </label>
                       </div>
                   </div>
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
             
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                  
</div>
 </div>
               <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color:darkolivegreen">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
             
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
           
              <div class="col-md-3">
                <div class="vendorbox1 pulse" style="background-color:darkorange">
                    <h5 style="padding: 12px;color:white;">Veiw Orders</h5>
<!--                   <input type="time" id="appt" name="appt" style="margin-left: 9px;">-->
                     <span style="background-color: white;margin-left: 9px;padding: 4px;border-radius: 6px; " ><span style="color:blue">34:46 PM</span> <i class="fa fa-clock-o" aria-hidden="true" style="color:blue"></i></span>
                    <br/>
                      
  
                    
       <div class="dropdown pt-3 pb-3 px-2">
       <!-- <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">show
        <span class="caret"></span></button>-->
           <button class="btn-click dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">View
        <span class="caret"></span></button>
           <div class="dropdown-menu container" role="menu" aria-labelledby="menu1">
            <div role="presentation" class="row ">
                  <div class="col-md-6">
                   <div class="py-2">
                       <div class="oitm">
                             <span class="" name="Orderid">Order ID :</span>&nbsp;&nbsp;<span class="" name="orderk">NC102-123_1444</span>
                       </div>
                       <div class="oitu"><span class="" name="Usernane">Product Nane:</span>&nbsp;&nbsp;<span class="">food</span></div>
                       <div class="oitd"><span class="" name="Discout">Discout :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitt"><span class="" name="tax">Tax :</span>&nbsp;&nbsp;<span class="">10%</span></div>
                       <div class="oitli"><span class="" name="timeline">Sub Total :</span>&nbsp;&nbsp;<span class="">300</span></div>
                   </div>
                 <label class="btn" style="font-size: 11px;">
    <input type="checkbox" autocomplete="off"> Check
  </label>
                
      
                   
                </div>
                <div class="col-md-6">
                
                   <img class="img" src="https://test.nextclick.in/uploads/food_item_image/food_item_731.jpg" style="width:80%">
                </div>
            
                
                <div class="row py-3">
                  <div class="col-sm-6">
                      <a href="#" class="accept">ACCEPT</a>
                      
                    </div>
                    <div class="col-sm-6">
                     
                      <a href="#" class="deny">REJECT</a>
                    </div>
                   
                </div>
               </div>
           
           </div>

      </div>                  
                    
</div>
 </div>
           
        </div>
          
        <!--end second -->
          
        </div>
        
    