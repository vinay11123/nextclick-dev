 
<div ng-if="UserInfo.approvedTermsAndConditions || !UserInfo.isAuthenticated">
    <ncy-breadcrumb></ncy-breadcrumb>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
              <div class="panel panel-default" style="margin-top:90px;">
                    <div class="panel-body" style="max-height:600px;  background-color:white;">
                        <div style="text-align:center;">
                            <h1>Terms and Conditions</h1>
                               <?php foreach ($vendor as $req):?>
                            <p>
                                <?php echo  $req['name'];?>
                            </p>
                            <p>
                                <?php echo $req['category']['terms'];?>
                            </p>
                              <?php endforeach;?>
                        </div>
                    </div>
                </div>
               
           
        </div>
    </div>
</div>