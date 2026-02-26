<?php

class News extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        $this->load->model('news_model');
        $this->load->model('news_category_model');
        $this->load->model('local_news_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('setting_model');
    }
    
    /**
     * @author Trupti
     * @desc To Manage News Categories
     * @param string $type
     */
    public function news_categories($type = 'r'){
        /* if (! $this->ion_auth_acl->has_permission('news_categories'))
            redirect('admin'); */
            
            if ($type == 'c') {
                $this->form_validation->set_rules($this->news_category_model->rules);
                if (empty($_FILES['file']['name'])) {
                    $this->form_validation->set_rules('file', 'News Category Image', 'required');
                }
                if ($this->form_validation->run() == FALSE) {
                    $this->news_categories('r');
                } else {
                    $id = $this->news_category_model->insert([
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ]);
                    
                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "news_category", $id, '', 'no');
                    redirect('news_categories/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'News';
                $this->data['content'] = 'news/category';
                $this->data['nav_type'] = 'news_categories';
                $this->data['news_categories'] = $this->news_category_model->order_by('id', 'ASCE')->get_all();
                $this->_render_page($this->template, $this->data);
            }  elseif ($type == 'u') {
                $this->form_validation->set_rules($this->news_category_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $this->news_category_model->update([
                        'id' => $this->input->post('id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');
                    
                    if ($_FILES['file']['name'] !== '') {
                        $path = $_FILES['file']['name'];
                        //$this->file_up("file", "news_category", $this->input->post('id'), '', 'no');
                        unlink('uploads/' . 'news_category' . '_image/' . 'news_category' . '_' . $this->input->post('id') . '.jpg');
                        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'news_category' . '_image/' . 'news_category' . '_' . $this->input->post('id') . '.jpg');
                    }
                    redirect('news_categories/r', 'refresh');
                }
            }elseif ($type == 'd') {
                echo $this->news_category_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit News Category';
                $this->data['content'] = 'news/edit';
                $this->data['nav_type'] = 'news_categories';
                $this->data['type'] = 'news_categories';
                $this->data['category'] = $this->news_category_model->where('id',$this->input->get('id'))->get();
                $this->data['i'] = $this->news_category_model->where('file',$this->input->get('file'))->get();
                $this->data['news_categories'] = $this->news_category_model->order_by('id', 'DESC')->where('id', $this->input->get('id'))->get();
                $this->_render_page($this->template, $this->data);
            }
    }
    
    /**
     * @author Mehar
     * @desc To Manage News
     * 
     * @param string $type
     */
    public function news($type = 'r'){
        /* if (! $this->ion_auth_acl->has_permission('news'))
            redirect('admin'); */
            
            if ($type == 'c') {
                $this->form_validation->set_rules($this->news_model->rules);
                if (empty($_FILES['file']['name'])) {
                    $this->form_validation->set_rules('file', 'News Image', 'required');
                }
                if ($this->form_validation->run() == FALSE) {
                    $this->data['title'] = 'News Add';
                    $this->data['content'] = 'news/add_news';
                    $this->data['nav_type'] = 'news';
                    $this->data['news_categories'] = $this->news_category_model->order_by('id', 'DESC')->where('status', 1)->get_all();
                    $this->_render_page($this->template, $this->data);
                } else {
                    $id = $this->news_model->insert([
                        'title' => $this->input->post('title'),
                        'news_date' => $this->input->post('news_date'),
                        'video_link' => $this->input->post('url'),
                        'category' => $this->input->post('category'),
                        'news' => $this->input->post('news'),
                    ]);
                    $path = $_FILES['file']['name'];
                    $this->file_up("file", "news", $id, '', 'no');
                    //move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'news' . '_image/' . 'news' . '_' . $this->input->post('id') . '.jpg');
                    redirect('news/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'News';
                $this->data['content'] = 'news/news';
                $this->data['nav_type'] = 'news';
                $this->data['news'] = $this->news_model->order_by('id', 'DESC')->where('status', 1)->get_all();
                $this->data['news_categories'] = $this->news_category_model->order_by('id', 'DESC')->where('status', 1)->get_all();
                $this->_render_page($this->template, $this->data);
            } elseif ($type == 'u') {
                $this->form_validation->set_rules($this->news_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->news('edit');
                } else {
                    $this->news_model->update([
                        'id' => $this->input->post('id'),
                        'title' => $this->input->post('title'),
                        'news_date' => $this->input->post('news_date'),
                        'video_link' => $this->input->post('url'),
                        'category' => $this->input->post('category'),
                        'news' => $this->input->post('news'),
                    ], 'id');
                    
                    if ($_FILES['file']['name'] !== '') {
                        $path = $_FILES['file']['name'];
                        //$this->file_up("file", "news", $this->input->post('id'), '', 'no');
                        unlink('uploads/' . 'news' . '_image/' . 'news' . '_' . $this->input->post('id') . '.jpg');
                        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'news' . '_image/' . 'news' . '_' . $this->input->post('id') . '.jpg');
                    }
                    redirect('news/r', 'refresh');
                }
            } elseif ($type == 'd') {
                echo $this->news_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit News';
                $this->data['content'] = 'news/edit';
                $this->data['type'] = 'news';
                $this->data['nav_type'] = 'news';
                $this->data['news'] = $this->news_model->where('id',$this->input->get('id'))->get();
                $this->data['news_category'] = $this->news_category_model->where('status', 1)->get_all();
                $this->_render_page($this->template, $this->data);
            }
    }
    
    public function local_news($type = 'r'){
        if($type == 'r'){
        $this->data['title'] = 'Local News';
        $this->data['content'] = 'news/local_news';
        $this->data['nav_type'] = 'local_news';
        $this->data['local_news'] = $this->local_news_model->order_by('id', 'DESC')->get_all();
       // print_array( $this->data['local_news']);
        $this->data['news_categories'] = $this->news_category_model->get_all();
        $this->_render_page($this->template, $this->data);
        }elseif ($type == 'u'){
            $this->form_validation->set_rules($this->local_news_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else{
             $this->local_news_model->update([
            //'id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'category' => $this->input->post('category'),
            'video_link' => $this->input->post('video_link'),
            'news' => $this->input->post('news'),
            'created_at'=>$this->input->post('created_at')
             ], $this->input->post('id'));
            if ($_FILES['file']['name'] !== '') {
                $path = $_FILES['file']['name'];
                $this->file_up("file", "local_news", $this->input->post('id'), '', 'no');
            }
            redirect('local_news/r', 'refresh');
                }
        }elseif($type == 'edit'){
            $this->data['title'] = 'Edit News';
            $this->data['content'] = 'news/edit_local_news';
            $this->data['nav_type'] = 'local_news';
            $this->data['type'] = 'local_news';
            $this->data['local_news']=$this->local_news_model->order_by('id', 'DESC')->where('id', $this->input->get('id'))->get();
            $this->data['news_categories'] = $this->news_category_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'status'){
            $status = $this->local_news_model->update([
                'user_id' => $this->input->post('user_id'),
                'status' => ($this->input->post('is_checked') == 'true') ? 2 : 1
            ], 'user_id');
            if($_POST['is_checked'] == 'true'){
                $id = $this->wallet_transaction_model->insert([
                    'user_id' => $this->input->post('user_id'),
                    'type' => 'CREDIT',
                    'cash' => floatval($this->setting_model->where('key','pay_per_news')->get()['value']),
                ]);
            }
            echo json_encode($status);
            
        }elseif ($type == 'd') {
            echo $this->local_news_model->delete(['id' => $this->input->post('id')]);
        }
    }
}

