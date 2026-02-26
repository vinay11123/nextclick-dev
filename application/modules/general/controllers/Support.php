<?php

class Support extends MY_Controller
{
	public function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('support_model');
		$this->load->model('customer_support_model');
		$this->load->model('request_model');
         $this->load->model('app_details_model');
         $this->load->library('pagination');
         $this->load->model('notification_type_model');
    }
 
	public function customer($type = 'r',$rowno = 0 )
	{

		$l_user_id=$this->session->user_id;
		$details=$this->db->query("SELECT primary_intent from users where id=".$l_user_id)->result_array();
		$p_in=$details[0]['primary_intent'];
		if($type == 'r'){
		
        $rowperpage = $noofrows ? $noofrows : 10;

        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
            $this->data['title'] = 'Customer Support';
            $this->data['content'] = 'general/customer_support';
            $this->data['nav_type'] = 'customer_support';

        if ($this->input->post('noofrows') != NULL) {
 
            $noofrows = $this->input->post('noofrows');
            $rowperpage = $noofrows ? $noofrows : 10;

        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
		
		$allcount_query= 'SELECT cs.*, a.app_name,u.username, u.email, u.phone, u.first_name,u.last_name,vl.customer_name,vl.business_name FROM `customer_support` as cs left join app_details as a on a.id =cs.app_details_id left join users as u on u.id = cs.created_user_id left join vendors_list as vl on vl.vendor_user_id = cs.created_user_id';

		$request_query='SELECT cs.*, a.app_name,u.username, u.email, u.phone, u.first_name,u.last_name,vl.customer_name,vl.business_name FROM `customer_support` as cs left join app_details as a on a.id =cs.app_details_id left join users as u on u.id = cs.created_user_id left join vendors_list as vl on vl.vendor_user_id = cs.created_user_id';

		if($p_in=='Tech Support')
		{

			$allcount_query= $allcount_query . "where cs.assigned_to=".$l_user_id;
			$request_query= $request_query . "where cs.assigned_to=".$l_user_id;
		}
		else{

			$allcount_query= $allcount_query;
			$request_query= $request_query;

		}

        }
		else{
			$allcount_query= 'SELECT cs.*, a.app_name,u.username, u.email, u.phone, u.first_name,u.last_name,vl.customer_name,vl.business_name FROM `customer_support` as cs left join app_details as a on a.id =cs.app_details_id left join users as u on u.id = cs.created_user_id left join vendors_list as vl on vl.vendor_user_id = cs.created_user_id';

		$request_query='SELECT cs.*, a.app_name,u.username, u.email, u.phone, u.first_name,u.last_name,vl.customer_name,vl.business_name FROM `customer_support` as cs left join app_details as a on a.id =cs.app_details_id left join users as u on u.id = cs.created_user_id left join vendors_list as vl on vl.vendor_user_id = cs.created_user_id';

		if($p_in=='Tech Support')
		{

			$allcount_query= $allcount_query . "where cs.assigned_to=".$l_user_id;
			$request_query= $request_query . "where cs.assigned_to=".$l_user_id;
		}
		else{

			$allcount_query= $allcount_query;
			$request_query= $request_query;

		}
		}

        if ($this->input->post('app_name') != NULL) {
            
             $group = $this->input->post('app_name');
	
				if($p_in=='Tech Support')
				{
						$allcount_query= $allcount_query ." and a.id='$group'";
						$request_query= $request_query. " and a.id='$group'";

				}
				else{			
					
					$allcount_query= $allcount_query ." where a.id='$group'";
						$request_query= $request_query. " where a.id='$group'";

				}
        }
		
		if ($this->input->post('severity') != NULL) {
            
             $severity = $this->input->post('severity');
			if($p_in=='Tech Support')
			{
				$allcount_query= $allcount_query ." and cs.severity='$severity'";
				$request_query= $request_query. " and cs.severity='$severity'";


			}
			else{

				  $allcount_query= $allcount_query ." and cs.severity='$severity'";
				$request_query= $request_query. " and cs.severity='$severity'";
			}
        }

if ($this->input->post('status') != NULL) {
            
             $status = $this->input->post('status');
		if($p_in=='Tech Support')
		{
			
				$allcount_query= $allcount_query ." and cs.status='$status'";
				$request_query= $request_query. " and cs.status='$status'";
			
		}
		else{
				$allcount_query= $allcount_query ." and cs.status='$status'";
				$request_query= $request_query. " and cs.status='$status'";
		}
	}		
    if ($this->input->post('content') != NULL) {
            
             $group = $this->input->post('content');
		if($p_in=='Tech Support')
		{
					$allcount_query= $allcount_query ." and cs.request_type like '%$group%'";
				$request_query= $request_query. " and cs.request_type like '%$group%'";

		}
		else{
					$allcount_query= $allcount_query ." and cs.request_type like '%$group%'";
				$request_query= $request_query. " and cs.request_type like '%$group%'";

		}
    }
		

        if($this->input->post('submit') == NULL)
        { 
			if($p_in=='Tech Support')
			{
				$allcount = $this->db->query($allcount_query)->num_rows();

				$this->data['support_requests'] = $this->db->query($request_query ." order by id desc  limit ". $rowno . ',' . $rowperpage)->result_array();
				
			}
			else{
				$allcount = $this->db->query($allcount_query)->num_rows();
				$this->data['support_requests'] = $this->db->query($request_query ." order by id desc limit ". $rowno . ',' . $rowperpage)->result_array();
			}
        }
		else{
		}
		
		

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = "</li>";
        $config['base_url'] = base_url() . 'general/support/customer/r';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['row'] = $rowno;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
        } 
		
		
		
        else if($type == 'c'){

        	$this->form_validation->set_rules($this->support_model->rules['create_rules']);
        	 if ($this->form_validation->run() == FALSE) {

            $this->data['title'] = 'Support';
            $this->data['content'] = 'general/create_support';
            $this->data['nav_type'] = 'support';
             $this->data['request_type'] = $this->request_model->get_all();
              $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
           } else {

           	$id = $this->support_model->insert([
                    'token_no' => $this->input->post('token'),
                    'app_details_id' => $this->input->post('app_details_id'),
                    'request_type_id'=> $this->input->post('req_id'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('req_content'),
                    'query_owner_id' => 1,
                    'created_user_id' => $this->ion_auth->get_user_id()
                    
                ]);
                redirect('general/support/support_queries/r/0', 'refresh');
           }

        }  else if($type == 'delete'){

                $id = base64_decode(base64_decode($this->input->get('id')));
                $this->support_model->delete([
                    'id' => $id
                ]); 
            redirect('general/support/support_queries/r/0', 'refresh');

         } elseif ($type == 'edit') {

     $id = base64_decode(base64_decode($this->input->get('id')));
 
            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'general/edit_support';
            $this->data['type'] = 'request';
            $this->data['nav_type'] = 'support';
            $this->data['request_type'] = $this->request_model->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->data['support_requests'] = $this->db->query("SELECT *  FROM customer_support WHERE id = '$id'")->result_array();
			$user_id=$this->data['support_requests'][0]['created_user_id'];
			
			$this->data['users'] = $this->db->query("SELECT *  FROM users WHERE id = '$user_id'")->result_array();
			$this->data['users_tech'] = $this->db->query("SELECT *  FROM users WHERE primary_intent = 'Tech Support'")->result_array();
			
			$this->_render_page($this->template, $this->data);

			
        } elseif ($type == 'u') {
						$severity=$this->input->post('severity');
			$status=$this->input->post('status');
			
			switch ($severity) {
  case "0":
    $severity_text="Low";
    break;
  case "1":
    $severity_text= "Medium";
    break;
  case "2":
    $severity_text="High";
    break;
  case "3":
    $severity_text= "Critical";
	break;
}

switch ($status) {
  case "1":
    $status_text="Open";
    break;
  case "2":
    $status_text= "Working";
    break;
  case "3":
    $status_text="Closed";
	break;
}
					$user_id=$this->ion_auth->get_user_id();
					if($this->input->post('assigned_to') == $user_id)
					{
                	 $this->customer_support_model->update([
                    'id' => $this->input->post('id'),
					'severity' =>$this->input->post('severity'),
					'status' =>$this->input->post('status'),
					'severity_text'=>$severity_text,
					'status_text'=>$status_text,
					'assigned_to' =>$this->input->post('assigned_to'),
					'comment' =>$this->input->post('comment')
                ], 'id');
					}
					else{
						 $this->customer_support_model->update([
                    'id' => $this->input->post('id'),
					'severity' =>$this->input->post('severity'),
					'status' =>$this->input->post('status'),
					'severity_text'=>$severity_text,
					'status_text'=>$status_text,
					'assigned_to' =>$this->input->post('assigned_to'),
					'comment' =>$this->input->post('comment'),
                    'assigned_by' => $this->ion_auth->get_user_id()
                ], 'id');
					}
				if($this->input->post('status') == 3) {
					$support_text = "Your ticket number ".$this->input->post('id'). " got closed";
				
				$id=$this->input->post('id');
				$this->data['support_requests'] = $this->db->query("SELECT *  FROM customer_support WHERE id = '$id'")->result_array();
				if($this->data['support_requests'][0]['app_details_id'] == 2) {				 
					$this->send_notification($user_id, VENDOR_APP_CODE, "Support Alert", $support_text, [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => VENDOR_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $id
                            ]);
				 }
				 if ($this->data['support_requests'][0]['app_details_id'] == 1) {
					 $this->send_notification($user_id, USER_APP_CODE, "Support Alert", $support_text, [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $id
                            ]);
				 }
				 if ($this->data['support_requests'][0]['app_details_id'] == 4) {
					 $this->send_notification($user_id, DELIVERY_APP_CODE, "Support Alert", $support_text, [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => DELIVERY_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $id
                            ]);
					 
				 }
				 }
                redirect('general/support/customer/r/0', 'refresh');
        }



 
	}
	
    public function support_queries($type = 'r',$rowno = 0 )
    {
        if($type == 'r'){

        $rowperpage = $noofrows ? $noofrows : 10;

        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
            $this->data['title'] = 'Support';
            $this->data['content'] = 'general/support';
            $this->data['nav_type'] = 'support';

        if ($this->input->post('noofrows') != NULL) {
 
            $noofrows = $this->input->post('noofrows');
            $rowperpage = $noofrows ? $noofrows : 10;

        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
 $allcount = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s
           JOIN app_details AS ad ON s.app_details_id  = ad.id
           JOIN request_type AS rt ON s.request_type_id  = rt.id")->num_rows();
                       
            
          $this->data['support_requests'] = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s JOIN app_details AS ad ON s.app_details_id  = ad.id JOIN request_type AS rt ON s.request_type_id  = rt.id LIMIT " . $rowno . ',' . $rowperpage)->result_array();
        }
        if ($this->input->post('app_name') != NULL) {
            
             $group = $this->input->post('app_name');
 

  $allcount = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s
           JOIN app_details AS ad ON s.app_details_id  = ad.id
           JOIN request_type AS rt ON s.request_type_id  = rt.id and ad.app_name like '$group'")->num_rows();
 

     $this->data['support_requests'] = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s JOIN app_details AS ad ON s.app_details_id  = ad.id JOIN request_type AS rt ON s.request_type_id  = rt.id and ad.app_name like '$group' LIMIT " . $rowno . ',' . $rowperpage)->result_array();
        }


    if ($this->input->post('content') != NULL) {
            
             $group = $this->input->post('content');
     $allcount = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s
           JOIN app_details AS ad ON s.app_details_id  = ad.id
           JOIN request_type AS rt ON s.request_type_id  = rt.id and rt.title like '$group'")->num_rows();
     $this->data['support_requests'] = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s JOIN app_details AS ad ON s.app_details_id  = ad.id JOIN request_type AS rt ON s.request_type_id  = rt.id and rt.title like '$group' LIMIT " . $rowno . ',' . $rowperpage)->result_array();

    }

        if($this->input->post('submit') == NULL)
        { 
           $allcount = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s
           JOIN app_details AS ad ON s.app_details_id  = ad.id
           JOIN request_type AS rt ON s.request_type_id  = rt.id")->num_rows();

     $this->data['support_requests'] = $this->db->query("SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s JOIN app_details AS ad ON s.app_details_id  = ad.id JOIN request_type AS rt ON s.request_type_id  = rt.id LIMIT " . $rowno . ',' . $rowperpage)->result_array();

        }
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = "</li>";
        $config['base_url'] = base_url() . 'general/support/support_queries/r';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['row'] = $rowno;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
        } 
        else if($type == 'c'){

        	$this->form_validation->set_rules($this->support_model->rules['create_rules']);
        	 if ($this->form_validation->run() == FALSE) {

            $this->data['title'] = 'Support';
            $this->data['content'] = 'general/create_support';
            $this->data['nav_type'] = 'support';
             $this->data['request_type'] = $this->request_model->get_all();
              $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
           } else {

           	$id = $this->support_model->insert([
                    'token_no' => $this->input->post('token'),
                    'app_details_id' => $this->input->post('app_details_id'),
                    'request_type_id'=> $this->input->post('req_id'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('req_content'),
                    'query_owner_id' => 1,
                    'created_user_id' => $this->ion_auth->get_user_id()
                    
                ]);
                redirect('general/support/support_queries/r/0', 'refresh');
           }

        }  else if($type == 'delete'){

                $id = base64_decode(base64_decode($this->input->get('id')));
                $this->support_model->delete([
                    'id' => $id
                ]); 
            redirect('general/support/support_queries/r/0', 'refresh');

         } elseif ($type == 'edit') {

     $id = base64_decode(base64_decode($this->input->get('id')));
 
            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'general/edit_support';
            $this->data['type'] = 'request';
            $this->data['nav_type'] = 'support';
            $this->data['request_type'] = $this->request_model->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->data['support_requests'] = $this->db->query("SELECT *  FROM support WHERE id = '$id'")->result_array();
            $this->_render_page($this->template, $this->data);

        } elseif ($type == 'u') {

                	 $this->support_model->update([
                    'id' => $this->input->post('id'),
                    'token_no' => $this->input->post('token'),
                    'app_details_id' => $this->input->post('app_details_id'),
                    'request_type_id'=> $this->input->post('req_id'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('req_content'),
                    'query_owner_id' => 1,
                    'created_user_id' => $this->ion_auth->get_user_id()
                ], 'id');
                redirect('general/support/support_queries/r/0', 'refresh');
        }



 

    }

}