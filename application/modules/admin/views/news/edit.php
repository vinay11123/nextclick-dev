<?php if($type == 'news_categories'){?>


    <!--Edit Category -->
    <!-- <div class="row">
    <div class="col-md-12">
   
       <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('category/r');?>">Listing Filters Data  <i class="fa fa-angle-double-left"></i> Category</a> 
   
    </div>
    </div>  -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Edit Category</h4>
            <form class="needs-validation" novalidate=""  action="<?php echo base_url('news_categories/u');?>" method="post" enctype="multipart/form-data">
           <div class="card-header">
           <div class="form-row">
                          <div class="form-group col-md-3">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control" required="" value="<?php echo $category['name'];?>">
                            <div class="invalid-feedback">Enter Valid Category Name?</div>
                            <input type="hidden" name="id" value="<?php echo $category['id'] ; ?>">
                        </div>
                         
                         <div class="form-group mb-0 col-md-4">
                            <label>Description</label>
                            <input type="text" name="desc" class="form-control" required="" value="<?php echo $category['desc'];?>">
                            <div class="invalid-feedback">Give some Description</div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Upload Image</label>
                            <input type="file" name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/news_category_image/news_category_<?php echo $category['id']; ?>.jpg">
                            
                            <div class="invalid-feedback">Upload Image?</div>
                        </div>
                        <div class="form-group col-md-1">
                        <img src="<?php echo base_url(); ?>uploads/news_category_image/news_category_<?php echo $category['id']; ?>.jpg?<?php echo time();?>">

                        <img id="blah" src="#" alt="" /> 
                        </div>

                         <div class="form-group col-md-12">
                         <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> 
<!-- <button class="btn btn-primary mt-27 ">Update</button> -->
                           
                        </div>
           
            </div>
            </div>
            </form>

        </div>
    </div>
  <?php }elseif($type == 'news'){?>
  <script type="text/javascript"> var url = "<?php echo $news['video_link'];?>";</script>
  <div class="card">
	<div class="card-header ven1">Edit News</div>
	<div class="card-body">
		<div class="row">
        <div class="col col-sm col-md" >
        	<form action="<?php echo base_url('news/u')?>" method="post" enctype="multipart/form-data">
                  <div class="form-group row">
                    <label for="title" class="col-4 col-form-label">News Title</label> 
                    <div class="col-6">
                      <input id="title" name="title" placeholder="News Title" type="text" class="form-control" value="<?php echo $news['title']?>">
                      <?php echo form_error('title', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="date" class="col-4 col-form-label">News Date</label> 
                    <div class="col-6">
                      <input id="news-date" name="news_date" placeholder="yyyy-mm-dd" type="text" class="form-control" value="<?php echo $news['news_date']?>">
                      <?php echo form_error('date', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="category" class="col-4 col-form-label">News Category</label> 
                    <div class="col-6">
                      <select id="category" name="category" class="custom-select" >
                        <option value="0" selected disabled>--Select--</option>
                        <?php foreach ($news_category as $category){ ?>
                        	<option value="<?php echo $category['id']?>" <?php echo ($category['id'] == $news['category'])? 'selected':'';?>><?php echo $category['name']?></option>
                        <?php }?>
                      </select>
                      <?php echo form_error('category', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="type" class="col-4 col-form-label">Content Type</label> 
                    <div class="col-6">
                      <select id="type" name="type" class="custom-select" >
                      	<option value="0" selected disabled>--Select--</option>
                      	<?php if(empty($news['video_link'])){?>
                            <option value="1" selected>Standard Post</option>
                            <option value="2">Video Post</option>
                        <?php }else{?>
                            <option value="1" >Standard Post</option>
                            <option value="2" selected>Video Post</option>
                        <?php }?>
                      </select>
                    </div>
                  </div> 
                   <div class="form-group row link">
                    <label for="date" class="col-4 col-form-label">YouTube Link</label> 
                    <div class="col-6">
                      <input id="link" name="url" placeholder="http://abc.com" type="url" class="form-control" value="">
                      <?php echo form_error('url', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="type" class="col-4 col-form-label">Image</label> 
                    <div class="col-6">
                		<input type='file' name="file" class="form-control" onchange="readUrl(this);" />
                		<?php echo form_error('file', '<div style="color:red">', '</div>');?>
               			<img id="blah" src="<?php echo base_url();?>uploads/news_image/news_<?php echo $news['id'];?>.jpg?<?php echo time();?>" width="180" height="180" alt="your image" style="margin-top: 10px"/>
           			</div>
            	</div>
                 <!--  <div class="form-group row">
                    <div class="offset-4 col-12">
                    	<input type="hidden" name="id" value="<?php echo $news['id'];?>">
                    	<button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div> -->
                 
        </div>
        <div class="col col-sm-12 col-md-12" >
          	<textarea id="add_news" name="news" class="ckeditor" rows="10" data-sample-short><?php echo $news['news']?></textarea>
          	<?php echo form_error('news', '<div style="color:red">', '</div>');?>
        </div>
        <div class="form-group row">
                    <div class="offset-4 col-12 mt-4 pt-2">
                      <input type="hidden" name="id" value="<?php echo $news['id'];?>">
                      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
    </div>
	</div>
     </form>
</div>

  <?php }?>
                       