<div class="card">
	<div class="card-header ven">Add News</div>
	<div class="card-body">
		<div class="row">
        <div class="col col-sm col-md" >
        <script type="text/javascript"> var url = "<?php echo '';?>";</script>
        	<form action="<?php echo base_url('news/c')?>" method="post" enctype="multipart/form-data">
                  <div class="form-group row">
                    <label for="title" class="col-4 col-form-label">News Title</label> 
                    <div class="col-8">
                      <input id="title" name="title" placeholder="News Title" type="text" class="form-control" >
                      <?php echo form_error('title', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="date" class="col-4 col-form-label">News Date</label> 
                    <div class="col-8">
                      <input id="news-date" name="news_date" placeholder="yyyy-mm-dd" type="text" class="form-control" >
                      <?php echo form_error('date', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="category" class="col-4 col-form-label">News Category</label> 
                    <div class="col-8">
                      <select id="news-category" name="category" class="custom-select" >
                        <option value="0" selected disabled>--Select--</option>
                        <?php foreach ($news_categories as $category){?>
                        	<option value="<?php echo $category['id']?>"><?php echo $category['name']?></option>
                        <?php }?>
                      </select>
                      <?php echo form_error('category', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="type" class="col-4 col-form-label">Content Type</label> 
                    <div class="col-8">
                      <select id="type" name="type" class="custom-select" >
                      	<option value="0" selected disabled>--Select--</option>
                        <option value="1">Standard Post</option>
                        <option value="2">Video Post</option>
                      </select>
                    </div>
                  </div> 
                   <div class="form-group row link">
                    <label for="date" class="col-4 col-form-label">YouTube Link</label> 
                    <div class="col-8">
                      <input id="link" name="url" placeholder="http://abc.com" type="url" class="form-control" >
                      <?php echo form_error('url', '<div style="color:red">', '</div>');?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="type" class="col-4 col-form-label">Image</label> 
                    <div class="col-8">
                		<input type='file' name="file" onchange="readURL(this, 180, 180);" />
                		<?php echo form_error('file', '<div style="color:red">', '</div>');?>
               			<img id="blah" src="http://placehold.it/180" alt="your image" style="margin-top: 10px"/>
               			
           			</div>
            	</div>
                  <div class="form-group row">
                    <div class="offset-4 col-8">
                      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
        </div>
        <div class="col col-sm col-md" >
          	<textarea id="add_news" name="news" class="ckeditor" rows="10" data-sample-short></textarea>
          	<?php echo form_error('news', '<div style="color:red">', '</div>');?>
        </div>
        </form>
    </div>
	</div>
    
</div>