<div class="card">
	<div class="card-header ven1">Edit Local News</div>
	<div class="card-body">
		<div class="row">
        <div class="col col-sm col-md" >
        	<form action="<?php echo base_url('local_news/u')?>" method="post" enctype="multipart/form-data">
                  <div class="form-group row">
                    <label for="title" class="col-4 col-form-label">News Title</label> 
                    <div class="col-6">
                      <input id="title" name="title" placeholder="News Title" type="text" class="form-control" value="<?php echo $local_news['title']?>">
                      <?php echo form_error('title', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
         	<input type="hidden" name="id" value="<?php echo $local_news['id'];?>">
           
           <div class="form-group row">
                    <label  class="col-4 col-form-label">Vedio</label> 
                    <div class="col-6">
                      <input name="video_link" placeholder="News Title" type="text" class="form-control" value="<?php echo $local_news['video_link']?>">
                      <?php echo form_error('title', '<div style="color:red">', '</div>');?>
                    </div>
           </div>
           <div class="form-group row">
                    <label  class="col-4 col-form-label">News Date</label> 
                    <div class="col-6">
                      <input name="created_at" placeholder="News Title" type="text" class="form-control" value="<?php echo $local_news['created_at']?>">
                      <?php echo form_error('title', '<div style="color:red">', '</div>');?>
                    </div>
           </div>
          <div class="form-group row">
            <label  class="col-4 col-form-label">Category</label> 
             <div class="col-6">
               <select class="form-control" name="category" required="">
                  <option value="0" selected disabled>select</option>
                   <?php foreach ($news_categories as $category):?>
                   	<option value="<?php echo $category['id'];?>" <?php echo ($category['id'] == $local_news['category'])? 'selected': '';?>><?php echo $category['name']?></option>
                   <?php endforeach;?>
                </select>
                </div>
                <div class="invalid-feedback">Select Category Name?</div>
          </div>
             <div class="form-group row">
                    <label for="type" class="col-4 col-form-label">Image</label> 
                    <div class="col-6">
                		<input type='file' name="file" class="form-control" onchange="readUrl(this);" value="<?php echo base_url(); ?>uploads/local_news_image/local_news_<?php echo $local_news['id']; ?>.jpg" />
                		<?php echo form_error('file', '<div style="color:red">', '</div>');?>
               			<img id="blah" src="<?php echo base_url();?>uploads/local_news_image/local_news_<?php echo $local_news['id'];?>.jpg?<?php echo time();?>"  style="height: 177px; width: 211px; margin: 10px;"/>
           			</div>
            	</div>
              <div class="form-group row">
              <label for="type" class="col-3 col-form-label">Content</label>
              <div class="col col-sm col-md" >
              <textarea id="local_news" name="news" class="ckeditor" rows="10" data-sample-short><?php echo $local_news['news']?></textarea>
                <?php echo form_error('news', '<div style="color:red">', '</div>');?>
               </div>
      </div>
           <div class="form-group col-md-12 mt-4">
                        <!--   <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> -->
                <button class="btn btn-primary mt-27 ">Update</button>
		   </div>
    </div>
	</div>
</div>