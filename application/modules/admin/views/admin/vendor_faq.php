<!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
    <h4 class="ven">Check FAQ's</h4>
    <form class="needs-validation" novalidate=""
      action="<?php echo base_url('support/c');?>" method="post"
      enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
          <div class="form-group mb-0 col-md-10">
            <?php foreach ($faq as $category):?>
             
             <div class="ques row col-md-12" style="background-color: #8080804f;border-radius: 8px;margin:10px 0px; ">
             <!-- <input type="text" class="form-control col-md-10" 
              name="question" required="" placeholder="Question" value="<?php echo $category['question']?>"> --> <!-- <a href="" onclick="myFunction()">View</a>  -->
             <label class="col-md-12 collapsible">Question. <?php echo $category['question']?>???<i class="fa fa-sort-down  " style="float:right;font-size: 24px;padding: 0 10px 1px 0;"></i></label> 
               <!--  <button type="button" class="btn btn-primary collapsible col-md-2">View</button>
 -->        </div>
              <div style="display:none" class="ans easeIn col" >
                <label class="col-md-12">Answer:<?php echo $category['answer'];?></label> 
             
         <!-- <p >A:<?php echo $category['answer'];?></p> -->
            <!-- <textarea id="request_desc" name="answer" class="ckeditor" rows="10" data-sample-short disabled=""><?php echo $category['answer'];?></textarea> -->
           <?php echo form_error('request_content', '<div style="color:red">', '</div>');?>
         </div>
             <?php endforeach;?>
            
          </div>
          
         
          </div>
      </div>
    </form>
</div>
</div>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

$('.collapsible').click(function(){
  var answer = $(this).parent().next();//.find('div');
  
  if ($(answer).css('display') === "block") {
      //AnsDiv.style.display = "none";
       $(answer).slideUp();
     // $(AnsDiv).css('display','none');
      //$(AnsDiv).removeClass('animate__slideInDown');
    } else {
          //    $(AnsDiv).css('display','block');
              $(answer).slideDown();
    }
});
</script>
<style type="text/css">
 
.ans{
  animation-duration: :2s !important;
  padding-left: 0px;
}

</style>