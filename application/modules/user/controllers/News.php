<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class News extends MY_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->model('news_category_model');
        $this->load->model('local_news_model');
        $this->load->model('location_model');
    }
    /**
     * @author Mehar
     * @desc To get list of news categories and targeted category as well
     * @param string $target
     */
    public function news_categories_get($target = '') {
        if(empty($target)){
            $data = $this->news_category_model->fields('id, name')->get_all();
            if(! empty($data)){
                for ($i = 0; $i < count($data) ; $i++){
                    $data[$i]['image'] = base_url().'uploads/news_category_image/news_category_'.$data[$i]['id'].'.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $data = $this->news_category_model->fields('id, name')->where('id', $target)->get();
            $data['image'] = base_url().'uploads/news_category_image/news_category_'.$target.'.jpg';
            $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * To get the news
     *
     * @author Mehar
     * @param number $limit,offset
     */
    public function news_get($limit = 10, $offset = 0)
    {   
        $news_id = $this->input->get('news_id');
        if(! isset($news_id)){
            $data = $this->news_model->all($limit, $offset, $this->input->get('cat_id'));
            if (! empty($data['result'])) {
                foreach ($data['result'] as $d) {
                    $d->image = base_url() . 'uploads/news_image/news_' . $d->id . '.jpg';
                    $d->views_count = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$d->id." AND type = 1")->row()->count;
                    $d->times_ago = time_elapsed_string($d->created_at);
                }
            }
            $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $data = $this->news_model->where('id', $news_id)->get();
            $data['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$news_id." AND type = 1")->row()->count;
            $data['times_ago'] = time_elapsed_string($data['created_at']);
            $this->set_response_simple((empty($data)) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * To get the local news
     *
     * @author trupti
     * @param method, target
     */
    public function local_news_post($method = 'r', $target = NULL){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
      if($method == 'c'){
          $_POST = json_decode(file_get_contents("php://input"), TRUE);
          $this->form_validation->set_rules($this->local_news_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                ->where('longitude', $this->input->post('longitude'))
                ->get();
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }
                $id = $this->local_news_model->insert([
                    'user_id' => $token_data->id,
                    'title' => $this->input->post('title'),
                    'category' => $this->input->post('category'),
                    'video_link' => $this->input->post('video_link'),
                    'news' => $this->input->post('news'),
                    'location_id' => $l_id
                ]);
                if (!file_exists('uploads/local_news_image/')) {
                    mkdir('uploads/local_news_image/', 0777, true);
                }
                file_put_contents("./uploads/local_news_image/local_news_$id.jpg", base64_decode($this->input->post('local_news_image')));
                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        }elseif ($method == 'r'){
            if(empty($target)){
                $data = $this->local_news_model->get_all();
                if(! empty($data)){
                    for ($i = 0; $i < count($data) ; $i++){
                        $data[$i]['image'] = base_url().'uploads/local_news_image/local_news_'.$data[$i]['id'].'.jpg';
                        $data[$i]['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$data[$i]['id']." AND type = 2")->row()->count;
                        $data[$i]['times_ago'] = time_elapsed_string( $data[$i]['created_at']);
                    }
                }
                $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $data = $this->local_news_model->where('id', $target)->get();
                $data['image'] = base_url().'uploads/local_news_image/local_news_'.$target.'.jpg';
                $data['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$target." AND type = 2")->row()->count;
                $data['times_ago'] = time_elapsed_string($data['created_at']);
                $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif($method == 'u'){
            if(! empty($target)){
                $_POST = json_decode(file_get_contents("php://input"), TRUE);
                $this->form_validation->set_rules($this->local_news_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }else{
                    $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                    if ($v != '') {
                        $l_id = $v['id'];
                    } else {
                        $l_id = $this->location_model->insert([
                            'latitude' => $this->input->post('latitude'),
                            'longitude' => $this->input->post('longitude'),
                            'address' => $this->input->post('address')
                        ]);
                    }
                    $id =  $this->local_news_model->update([
                    'id' => $target,
                    'title' => $this->input->post('title'),
                    'category' => $this->input->post('category'),
                    'video_link' => $this->input->post('video_link'),
                    'news' => $this->input->post('news'),
                    'location_id' => $l_id
              ],'id');
              $this->set_response_simple($id, 'Updated..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
           }
        }elseif ($method == 'd'){
            $data = $this->local_news_model->where('id', $target)->delete();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);
            }
    }
    /**
     * To get the local news
     *
     * @author Trupti
     * @param $cat_id
     */
    public function local_news_get($cat_id = NULL,$target = NULL){
        if(! empty($cat_id))
        {
            if(! empty($target)){
                $data = $this->local_news_model->where('id', $target)->get();
                $data['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$target." AND type = 2")->row()->count;
                $data['times_ago'] = time_elapsed_string($data['created_at']);
                $this->set_response_simple((empty($data)) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $data = $this->local_news_model->all($cat_id,(isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll,(isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll);
                if (! empty($data['result'])) {
                    foreach ($data['result'] as $key => $val) {
                        $data['result'][$key]['image'] = base_url() . 'uploads/local_news_image/local_news_' . $val['id'] . '.jpg';
                        $data['result'][$key]['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$val['id']." AND type = 2")->row()->count;
                        $data['result'][$key]['times_ago'] = time_elapsed_string( $val['created_at']);
                    }
                }
            }
            
        }else{
            $data = $this->local_news_model->get_all();
            foreach ($data as $key => $val) {
                $data[$key]['image'] = base_url() . 'uploads/local_news_image/local_news_' . $val['id'] . '.jpg';
                $data[$key]['views_count'] = $this->db->query("SELECT COUNT(id) as count FROM `news_views` WHERE news_id = ".$val['id']." AND type = 2")->row()->count;
                $data[$key]['times_ago'] = time_elapsed_string( $val['created_at']);
            }
        }
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    public function view_count_post($type = 'update'){
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'update'){
            $is_exist = $this->db->select('device_id', 'type', 'news_id')->where(['device_id' => $this->input->post('device_id'), 'news_id' => $this->input->post('news_id'), 'type' => $this->input->post('type')])->get('news_views')->row();
            if(empty($is_exist)){
                $status = $this->db->insert('news_views',[
                    'device_id' => $this->input->post('device_id'),
                    'news_id' => $this->input->post('news_id'),
                    'type' => $this->input->post('type')
                ]);
                $this->set_response_simple($status, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }else{
                $this->set_response_simple($is_exist, 'Something went wrong..!', REST_Controller::HTTP_CONFLICT, FALSE);
            }
            
        }
    }
}
