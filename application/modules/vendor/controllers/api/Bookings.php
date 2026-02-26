<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Bookings extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('hosp_speciality_model');
        $this->load->model('hosp_doctor_model');
        $this->load->model('hosp_doctor_details_model');
        $this->load->model('od_category_model');
        $this->load->model('od_service_model');
        $this->load->model('od_service_details_model');
        $this->load->model('service_timings_model');
        $this->load->model('booking_model');
        $this->load->model('booking_item_model');
        $this->load->model('notifications_model');
        $this->load->model('notification_type_model');
    }

    /**
     * To manage specialities
     *
     * @access specialites table
     * @author Mehar
     *        
     * @param string $type
     */
    public function specialities_post($type = 'r', $target = 0)
    {
		
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->hosp_speciality_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->hosp_speciality_model->rules['create']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Speciality Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->hosp_speciality_model->insert([
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => 1
                ]);
                if ($id) {
                    if (! file_exists('uploads/' . 'speciality' . '_image/')) {
                        mkdir('uploads/' . 'speciality' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/speciality_image/speciality_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $where = " ";
                if (! empty($this->input->post('q'))) {
                    $where .= "OR name LIKE('%" . $this->input->post('q') . "%')";
                }
                $specialities = $this->hosp_speciality_model->where('created_user_id', $admin_ids)
                    ->where($where)
                    ->get_all();

                if (! empty($specialities)) {
                    foreach ($specialities as $key => $s) {
                        $specialities[$key]['image'] = base_url() . 'uploads/speciality_image/speciality_' . $s['id'] . '.jpg';
                    }
                }
                $this->set_response_simple($specialities, 'speciality list', REST_Controller::HTTP_OK, TRUE);
            } else {
                $speciality = $this->hosp_speciality_model->fields('id, name, desc, status')
                    ->with_doctors('fields: id, name, desc, experience, qualification, languages, fee, discount')
                    ->where('id', $target)
                    ->get();
                if (! empty($speciality['doctors'])) {
                    foreach ($speciality['doctors'] as $key => $doctor) {
                        $speciality['doctors'][$key]['image'] = base_url() . 'uploads/doctor_image/doctor_' . $doctor['id'] . '.jpg';
                    }
                }
                $speciality['image'] = base_url() . 'uploads/speciality_image/speciality_' . $target . '.jpg';
                $this->set_response_simple($speciality, 'Speciality', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->hosp_speciality_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->hosp_speciality_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ], 'id');

                if (! empty($this->input->post('image'))) {
                    if (! file_exists('uploads/' . 'speciality' . '_image/')) {
                        mkdir('uploads/' . 'speciality' . '_image/', 0777, true);
                    }
                    if (! file_exists(base_url() ."uploads/speciality_image/speciality_" . $this->input->post('id') . ".jpg")) {
                        unlink(base_url() ."uploads/speciality_image/speciality_" . $this->input->post('id') . ".jpg");
                    }
                    file_put_contents("./uploads/speciality_image/speciality_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                }
                $this->set_response_simple($is_updated, 'Speciality Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $this->hosp_speciality_model->delete([
                'id' => $target
            ]);
            $this->set_response_simple(NULL, 'Speciality deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * To get specialities
     *
     * @access specialites table
     * @author Mehar
     *
     * @param string $type
     */
    public function get_specialties_get($target = 0){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if(empty($target)){
            $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
            if(! empty($vendor)){
                $specialities = $this->db->query("select hs.id, hs.name, hs.desc, hs.status from vendors_specialties as vs join hosp_specialties hs on vs.speciality_id = hs.id where list_id = ".$vendor['id'])->result_array();
                if (! empty($specialities)) {
                    foreach ($specialities as $key => $s) {
                        $specialities[$key]['image'] = base_url() . 'uploads/speciality_image/speciality_' . $s['id'] . '.jpg';
                    }
                }
                $this->set_response_simple($specialities, 'success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }else{
            $speciality = $this->hosp_speciality_model->fields('id, name, desc, status')
            ->where('id', $target)
            ->get();
            $speciality['image'] = base_url() . 'uploads/speciality_image/speciality_' . $target . '.jpg';
            $this->set_response_simple($speciality, 'Speciality', REST_Controller::HTTP_OK, TRUE);
        }
        
    }

    /**
     * To manage doctors
     *
     * @access doctors table
     * @author Mehar
     *        
     * @param string $type
     */
    public function doctors_post($type = 'r', $list_type = NULL)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->hosp_doctor_model->user_id = $token_data->id;
        $this->hosp_doctor_details_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->hosp_doctor_details_model->rules['create']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Doctor Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->hosp_doctor_model->insert([
                    "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),
                    "status" => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3,
                ]);

                if ($id) {
                    $doctor_details_id = $this->hosp_doctor_details_model->insert([
                        "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),
                        "hosp_doctor_id" =>$id,
                        "name" => $this->input->post("name"),
                        "desc" => $this->input->post("qualification"),
                        "experience" => $this->input->post("experience"),
                        "languages" => $this->input->post("languages"),
                        "fee" => $this->input->post("fee"),
                        "discount" => $this->input->post("discount"),
                        "qualification" => $this->input->post("qualification"),
                        "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                        "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                    ]);
                    if(! empty($this->input->post('timings'))){
                        foreach ($this->input->post('timings') as $time){
                            $this->service_timings_model->insert([
                                'service_type' => 1,
                                'ref_id' => $doctor_details_id,
                                'start_time' => $time['start_time'],
                                'end_time' => $time['end_time']
                            ]);
                        }
                    }
                    if (! file_exists('uploads/' . 'doctors' . '_image/')) {
                        mkdir('uploads/' . 'doctors' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/doctors_image/doctors_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
            $this->set_response_simple($id, 'Doctor Created', REST_Controller::HTTP_CREATED, TRUE);
        } elseif ($type == 'doctor_details') {
            $doctor = $this->hosp_doctor_details_model->fields('id, hosp_specialty_id, hosp_doctor_id, name, desc, experience, languages, fee, discount, qualification, holidays, status')
                ->with_speciality('fields: id, name, desc')
                ->with_service_timings('fields: id, start_time, end_time, ref_id, service_type')
                ->where('id', $this->input->post("id"))
                ->get();
            $doctor['languages'] = (! empty($doctor['languages']))?array_map('trim', explode(",", $doctor['languages'])): [];
            $doctor['holidays'] = (! empty($doctor['holidays']))?array_map('trim', explode(",", $doctor['holidays'])): [];
            if (! empty($doctor['speciality'])) {
                $doctor['speciality']['image'] = base_url() . 'uploads/speciality_image/speciality_' . $doctor['speciality']['id'] . '.jpg';
            }
            $doctor['image'] = base_url() . 'uploads/doctors_image/doctors_' . $doctor['hosp_doctor_id'] . '.jpg';
            $this->set_response_simple($doctor, 'Doctor', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->hosp_doctor_details_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->hosp_doctor_details_model->update([
                    "id" => $this->input->post("id"),
                    "hosp_doctor_id" => $this->input->post("hosp_doctor_id"),
                    "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("qualification"),
                    "experience" => $this->input->post("experience"),
                    "languages" => $this->input->post("languages"),
                    "fee" => $this->input->post("fee"),
                    "discount" => $this->input->post("discount"),
                    "qualification" => $this->input->post("qualification"),
                    "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ], 'id');

                if(! empty($this->input->post('timings'))){
                    $this->db->where(['ref_id' => $this->input->post("id"), 'service_type' => 1,]);
                    $this->db->delete('services_timings');
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 1,
                            'ref_id' => $this->input->post("id"),
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
                $doctor = $this->hosp_doctor_details_model->where(["id" => $this->input->post("id "), "hosp_doctor_id" => $this->input->post("hosp_doctor_id"),])->get();
                if(! empty($doctor) && $doctor['created_user_id'] == $token_data->id){
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'doctors' . '_image/')) {
                            mkdir('uploads/' . 'doctors' . '_image/', 0777, true);
                        }
                        if (file_exists(base_url() ."uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg")) {
                            unlink(base_url() ."uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg");
                        }
                        file_put_contents("./uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                }
                $this->set_response_simple($is_updated, 'Doctor Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $this->hosp_doctor_details_model->delete([
                'id' => $this->input->post('id')
            ]);
            $is_exist = $this->hosp_doctor_model->where(['id' => $this->input->post('hosp_doctor_id'), 'created_user_id' => $token_data->id])->get();
            if(! empty($is_exist)){
                $this->hosp_doctor_model->delete([
                    'id' => $this->input->post('hosp_doctor_id')
                ]);
            }
            $this->set_response_simple(NULL, 'Doctor deleted..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'list'){
            if($list_type == 'admin_doctors' || $list_type == 'pending_doctors' || $list_type == 'approved_doctors'){
                $where = '';
                if ($list_type == 'admin_doctors'){
                    $where .= "hd.status = 1";
                }elseif ($list_type == 'pending_doctors'){
                    $where .= "hd.status =3 AND hd.created_user_id = ".$token_data->id;
                }elseif ($list_type == 'approved_doctors'){
                    $where .= "hd.status = 2 AND hd.created_user_id = ".$token_data->id;
                }
                
                if (! empty($this->input->post('q'))) {
                    $where .= " AND hdd.name like '%".$this->input->post('q')."%'";
                }
                if (! empty($this->input->post('hosp_specialty_id'))) {
                    $where .= " AND hdd.hosp_specialty_id = ".$this->input->post('hosp_specialty_id');
                }
                $doctors = $this->db->query(
                    "select  hdd.id, hdd.hosp_doctor_id, hdd.hosp_specialty_id, hdd.name, hdd.desc, hdd.qualification, hdd.experience, hdd.languages, hdd.fee, hdd.discount, hdd.holidays, hdd.created_user_id, hdd.status from hosp_doctors as hd
                    join hosp_doctors_details as hdd on (hd.id = hdd.hosp_doctor_id and hd.created_user_id = hdd.created_user_id)
                    where hdd.deleted_at is null and $where"
                    )->result_array();
                if (! empty($doctors)) {
                    foreach ($doctors as $key => $s) {
                        $doctors[$key]['holidays'] = (! empty($s['holidays'])) ?array_map('trim', explode(",", $s['holidays'])): [];
                        $doctors[$key]['image'] = base_url() . 'uploads/doctors_image/doctors_' . $s['hosp_doctor_id'] . '.jpg';
                    }
                }
                $this->set_response_simple($doctors, 'Doctors list', REST_Controller::HTTP_OK, TRUE);
            }else{ 
                if ($list_type == 'my_doctors'){
                    $where = '';
                    if (! empty($this->input->post('q'))) {
                        $where .= " AND hdd.name LIKE('%" . $this->input->post('q') . "%')";
                    }
                    if (! empty($this->input->post('hosp_specialty_id'))) {
                        $where .= " AND hdd.hosp_specialty_id = ".$this->input->post('hosp_specialty_id');
                    }
                    $doctors = $this->db->query(
                        "SELECT hdd.id, hdd.hosp_doctor_id, hdd.hosp_specialty_id, hdd.name, hdd.desc, hdd.qualification, hdd.experience, hdd.languages, hdd.fee, hdd.discount, hdd.holidays, hdd.created_user_id, hdd.status FROM vendors_hosp_doctors as vhd
                        join hosp_doctors hd on hd.id = vhd.hosp_doctor_id
                        join hosp_doctors_details as hdd on (hdd.hosp_doctor_id = hd.id and hdd.created_user_id = vhd.created_user_id)
                        where hdd.deleted_at is null and vhd.created_user_id = ".$token_data->id.$where)->result_array();
                    if (! empty($doctors)) {
                        foreach ($doctors as $key => $s) {
                            $doctors[$key]['holidays'] = (! empty($s['holidays'])) ?array_map('trim', explode(",", $s['holidays'])): [];
                            $doctors[$key]['image'] = base_url() . 'uploads/doctors_image/doctors_' . $s['hosp_doctor_id'] . '.jpg';
                            if(empty($s['service_timings'])){
                                $doctors[$key]['service_timings'] = [];
                            }
                        }
                    }
                    $this->set_response_simple($doctors, 'My Doctors list', REST_Controller::HTTP_OK, TRUE);
                }
            }
        }elseif ($type == 'add_to_my_doctors'){
            $doctor = $this->hosp_doctor_details_model->where(['created_user_id' => $token_data->id, "hosp_doctor_id" => $this->input->post("hosp_doctor_id"),])->get();
            if(! empty($doctor) && $doctor['created_user_id'] == $token_data->id){
                $doctor_details_id = $this->input->post("id");
                $is_updated = $this->hosp_doctor_details_model->update([
                    "id" => $this->input->post("id"),
                    "hosp_doctor_id" => $this->input->post("hosp_doctor_id"),
                    "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("qualification"),
                    "experience" => $this->input->post("experience"),
                    "languages" => $this->input->post("languages"),
                    "fee" => $this->input->post("fee"),
                    "discount" => $this->input->post("discount"),
                    "qualification" => $this->input->post("qualification"),
                    "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ], 'id');
                if(! empty($this->input->post('timings'))){
                    $this->db->where('ref_id', $this->input->post("id"));
                    $this->db->delete('services_timings');
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 1,
                            'ref_id' => $this->input->post("id"),
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
                $doctor = $this->hosp_doctor_details_model->where(["id" => $this->input->post("id "), "hosp_doctor_id" => $this->input->post("hosp_doctor_id"),])->get();
                if(! empty($doctor) && $doctor['created_user_id'] == $token_data->id){
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'doctors' . '_image/')) {
                            mkdir('uploads/' . 'doctors' . '_image/', 0777, true);
                        }
                        if (file_exists(base_url() ."uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg")) {
                            unlink(base_url() ."uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg");
                        }
                        file_put_contents("./uploads/doctors_image/doctors_" . $this->input->post('hosp_doctor_id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                }
            }else{
                $doctor_details_id = $this->hosp_doctor_details_model->insert([
                    "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),
                    "hosp_doctor_id" =>$this->input->post("hosp_doctor_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("qualification"),
                    "experience" => $this->input->post("experience"),
                    "languages" => $this->input->post("languages"),
                    "fee" => $this->input->post("fee"),
                    "discount" => $this->input->post("discount"),
                    "qualification" => $this->input->post("qualification"),
                    "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ]);
                if(! empty($this->input->post('timings'))){
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 1,
                            'ref_id' => $doctor_details_id,
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
            }
            $this->db->where([
                'hosp_doctor_id' => $this->input->post("hosp_doctor_id"),
                'created_user_id' => $token_data->id
            ]);
            $this->db->delete('vendors_hosp_doctors');
            $this->db->insert('vendors_hosp_doctors', [
                'hosp_doctor_id' => $this->input->post("hosp_doctor_id"),
                'hosp_doctor_details_id' => $doctor_details_id,
                'created_user_id' => $token_data->id
            ]);
            $this->set_response_simple(NULL, 'Doctor Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
        }
    }

    /**
     * To manage on demand categories
     *
     * @access od_categories table
     * @author Mehar
     *        
     * @param string $type
     */
    public function on_demand_categories_post(string $type = 'r', $target = NULL)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->od_category_model->user_id = $token_data->id;

        if ($type == 'c') {

            $this->form_validation->set_rules($this->od_category_model->rules['create']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'On Demand Category Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->od_category_model->insert([
                    'cat_id' => $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'],
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("desc")
                ]);
                if ($id) {
                    if (! file_exists('uploads/' . 'od_category' . '_image/')) {
                        mkdir('uploads/' . 'od_category' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/od_category_image/od_category_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $where = " ";
                if (! empty($this->input->post('q'))) {
                    $where .= "OR name LIKE('%" . $this->input->post('q') . "%')";
                }
                $od_category = $this->od_category_model->where('created_user_id', $admin_ids)
                    ->where($where)
                    ->get_all();
                if (! empty($od_category)) {
                    foreach ($od_category as $key => $s) {
                        $doctors[$key]['image'] = base_url() . 'uploads/od_category_image/od_category_' . $s['id'] . '.jpg';
                    }
                }
                $this->set_response_simple($od_category, 'On Demand Category list', REST_Controller::HTTP_OK, TRUE);
            } else {
                $od_category = $this->od_category_model->fields('id, cat_id, name, desc, status')
                    ->with_category('fields: id, name, desc')
                    ->with_od_services('fields: id, name, desc, service_duration, price, discount')
                    ->where('id', $target)
                    ->get();
                if (! empty($od_category['od_services'])) {
                    foreach ($od_category['od_services'] as $key => $od_cat) {
                        $od_category['od_services'][$key]['image'] = base_url() . 'uploads/od_service_image/od_service_' . $od_cat['id'] . '.jpg';
                    }
                }
                $od_category['image'] = base_url() . 'uploads/od_category_image/od_category_' . $target . '.jpg';
                $this->set_response_simple($od_category, 'On Demand Category', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->od_category_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->od_category_model->update([
                    "id" => $this->input->post("id"),
                    "cat_id" => $this->input->post("cat_id"),

                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("qualification")
                ], 'id');

                if (! empty($this->input->post('image'))) {
                    if (! file_exists('uploads/' . 'od_category' . '_image/')) {
                        mkdir('uploads/' . 'od_category' . '_image/', 0777, true);
                    }
                    if (! file_exists(base_url() ."uploads/od_category_image/od_category_" . $this->input->post('id') . ".jpg")) {
                        unlink(base_url() ."uploads/od_category_image/od_category_" . $this->input->post('id') . ".jpg");
                    }
                    file_put_contents("./uploads/od_category_image/od_category_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                }
                $this->set_response_simple($is_updated, 'On Demand Category Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $this->od_category_model->delete([
                'id' => $target
            ]);
            $this->set_response_simple(NULL, 'On Demand Category deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * To get on demand categories
     *
     * @access od_categories table
     * @author Mehar
     *
     * @param string $type
     */
    public function get_od_categories_get($target = 0){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if(empty($target)){
            $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
            if(! empty($vendor)){
                $od_category = $this->db->query("select oc.id, oc.name, oc.desc, oc.status from vendors_od_categories as voc join od_categories oc on voc.od_cat_id = oc.id where list_id = ".$vendor['id'])->result_array();
                if (! empty($od_category)) {
                    foreach ($od_category as $key => $s) {
                        $doctors[$key]['image'] = base_url() . 'uploads/od_category_image/od_category_' . $s['id'] . '.jpg';
                    }
                }
                $this->set_response_simple($od_category, 'On Demand Category list', REST_Controller::HTTP_OK, TRUE);
            }
        }else{
            $od_category = $this->od_category_model->fields('id, cat_id, name, desc, status')
            ->where('id', $target)
            ->get();
            $od_category['image'] = base_url() . 'uploads/od_category_image/od_category_' . $target . '.jpg';
            $this->set_response_simple($od_category, 'On Demand Category', REST_Controller::HTTP_OK, TRUE);
        }
        
    }

    /**
     * To manage on demand services
     *
     * @access od_servies table
     * @author Mehar
     *        
     * @param string $type
     */
    public function on_demand_services_post($type = 'r', $list_type = NULL)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->od_service_model->user_id = $token_data->id;
        $this->od_service_details_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->od_service_details_model->rules['create']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'On Demand Service Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->od_service_model->insert([
                    "od_cat_id" => $this->input->post("od_cat_id"),
                    "status" => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3,
                ]);

                if ($id) {
                    $od_service_details_id = $this->od_service_details_model->insert([
                        "od_cat_id" => $this->input->post("od_cat_id"),
                        'od_service_id' => $id,
                        "name" => $this->input->post("name"),
                        "desc" => $this->input->post("desc"),
                        "service_duration" => $this->input->post("service_duration"),
                        "price" => $this->input->post("price"),
                        "discount" => $this->input->post("discount"),
                        "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                        "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                    ]);
                    if(! empty($this->input->post('timings'))){
                        foreach ($this->input->post('timings') as $time){
                            $this->service_timings_model->insert([
                                'service_type' => 2,
                                'ref_id' => $od_service_details_id,
                                'start_time' => $time['start_time'],
                                'end_time' => $time['end_time']
                            ]);
                        }
                    }
                    if (! file_exists('uploads/' . 'od_service' . '_image/')) {
                        mkdir('uploads/' . 'od_service' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/od_service_image/od_service_" . $id . ".jpg".'?'.time(), base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        }elseif ($type == 'service_details') {
            $od_service = $this->od_service_details_model->fields('id, od_cat_id, name, desc, holidays, service_duration, price, discount, status')
            ->with_od_category('fields: id, name, desc')
            ->with_service_timings('fields: id, start_time, end_time, ref_id, service_type')
            ->where('id', $this->input->post('id'))
            ->get();
            if(empty($od_service['service_timings'])){
                $od_service['service_timings'] = [];
            }
            $od_service['holidays'] = (! empty($od_service['holidays']))?array_map('trim', explode(",", $od_service['holidays'])) : [];
            $od_service['image'] = base_url() . 'uploads/od_service_image/od_service_' . $this->input->post('id') . '.jpg'.'?'.time();
            if (! empty($od_service['od_category'])) {
                $od_service['od_category']['image'] = base_url() . 'uploads/od_category_image/od_category_' . $od_service['od_cat_id'] . '.jpg'.'?'.time();
            }
            $this->set_response_simple($od_service, 'On Demand Service', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->od_service_details_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->od_service_details_model->update([
                    "id" => $this->input->post("id"),
                    "od_service_id" => $this->input->post("od_service_id"),
                    "od_cat_id" => $this->input->post("od_cat_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("desc"),
                    "service_duration" => $this->input->post("service_duration"),
                    "price" => $this->input->post("price"),
                    "discount" => $this->input->post("discount"),
                    "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ], 'id');
                
                if(! empty($this->input->post('timings'))){
                    $this->db->where(['ref_id' => $this->input->post("id"), 'service_type' => 2]);
                    $this->db->delete('services_timings');
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 2,
                            'ref_id' => $this->input->post("id"),
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
                $od_service = $this->od_service_details_model->where(["od_service_id" => $this->input->post("od_service_id"), 'created_user_id' => $token_data->id])->get();
                if(! empty($od_service) && $od_service['created_user_id'] == $token_data->id){
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'od_service' . '_image/')) {
                            mkdir('uploads/' . 'od_service' . '_image/', 0777, true);
                        }
                        if (file_exists(base_url() ."uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg")) {
                            unlink(base_url() ."uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg");
                        }
                        file_put_contents("./uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                }
                $this->set_response_simple($is_updated, 'On Demand Service Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $this->od_service_details_model->delete([
                'id' => $this->input->post('id')
            ]);
            $is_exist = $this->od_service_model->where(['id' => $this->input->post('od_service_id'), 'created_user_id' => $token_data->id])->get();
            if(! empty($is_exist)){
                $this->od_service_model->delete([
                    'id' => $this->input->post('od_service_id')
                ]);
            }
            $this->set_response_simple(NULL, 'On Demand Service deleted..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'list'){
            if($list_type == 'admin_service' || $list_type == 'pending_service' || $list_type == 'approved_service'){
                $where = '';
                if ($list_type == 'admin_service'){
                    $where .= "os.status = 1";
                }elseif ($list_type == 'pending_service'){
                    $where .= "os.status =3 AND os.created_user_id = ".$token_data->id;
                }elseif ($list_type == 'approved_service'){
                    $where .= "os.status = 2 AND os.created_user_id = ".$token_data->id;
                }
                
                if (! empty($this->input->post('q'))) {
                    $where .= " AND osd.name like '%".$this->input->post('q')."%'";
                }
                if (! empty($this->input->post('od_cat_id'))) {
                    $where .= " AND osd.od_cat_id = ".$this->input->post('od_cat_id');
                }
                $od_service = $this->db->query(
                    "select  osd.id, osd.od_service_id, osd.od_cat_id, osd.name, osd.desc, osd.service_duration, osd.price, osd.discount, osd.holidays from od_services as os
                    join od_services_details as osd on (os.id = osd.od_service_id and os.created_user_id = osd.created_user_id)
                    where osd.deleted_at is null and  $where"
                    )->result_array();
                    if (! empty($od_service)) {
                        foreach ($od_service as $key => $s) {
                            $od_service[$key]['holidays'] = (! empty($s['holidays'])) ?array_map('trim', explode(",", $s['holidays'])): [];
                            $od_service[$key]['image'] = base_url() . 'uploads/od_service_image/od_service_' . $s['od_service_id'] . '.jpg';
                        }
                    }
                    $this->set_response_simple($od_service, 'On Demand Service list', REST_Controller::HTTP_OK, TRUE);
            }else{
                if ($list_type == 'my_service'){
                    $where = '';
                    if (! empty($this->input->post('q'))) {
                        $where .= " OR osd.name LIKE('%" . $this->input->post('q') . "%')";
                    }
                    $od_service = $this->db->query(
                        "SELECT osd.id, osd.od_service_id, osd.name, osd.desc, osd.service_duration, osd.price, osd.discount, osd.holidays FROM vendors_od_services as vos
                        join od_services as os on os.id = vos.od_service_id
                        join od_services_details as osd on (os.id = osd.od_service_id and os.created_user_id = osd.created_user_id)
                        where osd.deleted_at is null and vos.created_user_id = ".$token_data->id.$where)->result_array();
                    if (! empty($od_service)) {
                        foreach ($od_service as $key => $s) {
                            $od_service[$key]['holidays'] = (! empty($s['holidays'])) ?array_map('trim', explode(",", $s['holidays'])): [];
                            $od_service[$key]['image'] = base_url() . 'uploads/od_service_image/od_service_' . $s['od_service_id'] . '.jpg';
                        }
                    }
                    $this->set_response_simple($od_service, 'My On Demand Service list', REST_Controller::HTTP_OK, TRUE);
                }
            }
        }elseif ($type == 'add_to_my_services'){
            $od_service = $this->od_service_details_model->where(["od_service_id" => $this->input->post("od_service_id"), 'created_user_id' => $token_data->id])->get();
            if(! empty($od_service) && $od_service['created_user_id'] == $token_data->id){
                $od_service_details_id = $this->input->post("id");
                $is_updated = $this->od_service_details_model->update([
                    "id" => $this->input->post("id"),
                    "od_service_id" => $this->input->post("od_service_id"),
                    "od_cat_id" => $this->input->post("od_cat_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("desc"),
                    "service_duration" => $this->input->post("service_duration"),
                    "price" => $this->input->post("price"),
                    "holidays" => (! empty($this->input->post("holidays")))? implode(",", $this->input->post("holidays")): NULL,
                    "discount" => $this->input->post("discount"),
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ], 'id');
                
                if(! empty($this->input->post('timings'))){
                    $this->db->where('ref_id', $this->input->post("od_service_id"));
                    $this->db->delete('services_timings');
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 2,
                            'ref_id' => $this->input->post("id"),
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
                $od_service = $this->od_service_details_model->where(["od_service_id" => $this->input->post("od_service_id"), 'created_user_id' => $token_data->id])->get();
                if(! empty($od_service) && $od_service['created_user_id'] == $token_data->id){
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'od_service' . '_image/')) {
                            mkdir('uploads/' . 'od_service' . '_image/', 0777, true);
                        }
                        if (file_exists(base_url() ."uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg")) {
                            unlink(base_url() ."uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg");
                        }
                        file_put_contents("./uploads/od_service_image/od_service_" . $this->input->post('od_service_id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                }
            }else{
                $od_service_details_id = $this->od_service_details_model->insert([
                    "od_service_id" => $this->input->post("od_service_id"),
                    "od_cat_id" => $this->input->post("od_cat_id"),
                    "name" => $this->input->post("name"),
                    "desc" => $this->input->post("desc"),
                    "service_duration" => $this->input->post("service_duration"),
                    "price" => $this->input->post("price"),
                    "discount" => $this->input->post("discount"),
                    "status" => (empty($this->input->post("status")))? 1 : $this->input->post("status"),
                ]);
                if(! empty($this->input->post('timings'))){
                    foreach ($this->input->post('timings') as $time){
                        $this->service_timings_model->insert([
                            'service_type' => 2,
                            'ref_id' => $od_service_details_id,
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time']
                        ]);
                    }
                }
            }
            $this->db->where([
                'od_service_id' => $this->input->post("od_service_id"),
                'created_user_id' => $token_data->id
            ]);
            $this->db->delete('vendors_od_services');
            $this->db->insert('vendors_od_services', [
                'od_service_id' => $this->input->post("od_service_id"),
                "od_service_details_id" => $od_service_details_id,
                'created_user_id' => $token_data->id
            ]);
            $this->set_response_simple($token_data->id, 'Od service Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
        }
    }
    
    /**
     * @desc To get list of doctors created by given vendor
     * @author Mehar
     * 
     * @param number $vendor_id
     * @param number $speciality_id
     * @param number $target
     */
    public function get_vendor_doctors_get($vendor_id = 1, $speciality_id = 0, $target = 0){
        if (empty($target)) {
            $query = "SELECT hdd.id, hdd.hosp_doctor_id, hdd.hosp_specialty_id, hdd.name, hdd.desc, hdd.qualification, hdd.experience, hdd.languages, hdd.fee, hdd.discount, hdd.holidays, hdd.created_user_id, hdd.status FROM vendors_hosp_doctors as vhd
                        join hosp_doctors hd on hd.id = vhd.hosp_doctor_id
                        join hosp_doctors_details as hdd on (hdd.hosp_doctor_id = hd.id and hdd.created_user_id = vhd.created_user_id)
                        where hdd.deleted_at is null and vhd.created_user_id = ".$vendor_id." and hdd.status = 1 and hd.deleted_at is null and hdd.deleted_at is null";
            if(! empty($speciality_id)){
                $query .= " and hdd.hosp_specialty_id =".$speciality_id;
            }
            $doctors = $this->db->query($query)->result_array();
            
            if (! empty($doctors)) {
                foreach ($doctors as $key => $s) {
                    $doctors[$key]['holidays'] = array_map('trim', explode(",", $s['holidays']));
                    $doctors[$key]['image'] = base_url() . 'uploads/doctors_image/doctors_' . $s['hosp_doctor_id'] . '.jpg';
                    $doctors[$key]['service_timings'] = $this->db->query("SELECT id, start_time, end_time FROM services_timings where ref_id = ".$s['id']." and service_type = 1 and deleted_at is null;")->result_array();
                }
            }
            $this->set_response_simple($doctors, 'Doctors list', REST_Controller::HTTP_OK, TRUE);
        } else {
            $doctor = $this->hosp_doctor_details_model->fields('id, hosp_specialty_id, hosp_doctor_id, name, desc, experience, languages, fee, discount, qualification, holidays, status')
            ->with_speciality('fields: id, name, desc')
            ->where('id', $target)
            ->get();
            $doctor['service_timings'] = $this->db->query("SELECT id, start_time, end_time FROM services_timings where ref_id = ".$doctor['id']." and service_type = 1 and deleted_at is null;")->result_array();
            $doctor['languages'] = array_map('trim', explode(",", $doctor['languages']));
            $doctor['holidays'] = array_map('trim', explode(",", $doctor['holidays']));
            if (! empty($doctor['speciality'])) {
                $doctor['speciality']['image'] = base_url() . 'uploads/speciality_image/speciality_' . $doctor['speciality']['id'] . '.jpg';
            }
            $doctor['image'] = base_url() . 'uploads/doctors_image/doctors_' . $doctor['hosp_doctor_id'] . '.jpg';
            $this->set_response_simple($doctor, 'Doctor', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * @desc To get list of doctors created by given vendor
     * @author Mehar
     *
     * @param number $vendor_id
     * @param number $od_cat_id
     * @param number $target
     */
    public function get_vendor_od_services_get($vendor_id = 1, $od_cat_id = 0, $target = 0){
        if (empty($target)) {
            $query = "SELECT osd.id, osd.od_cat_id, osd.od_service_id, osd.name, osd.desc, osd.service_duration, osd.price, osd.discount FROM vendors_od_services as vos
                        join od_services as os on os.id = vos.od_service_id
                        join od_services_details as osd on (os.id = osd.od_service_id and os.created_user_id = osd.created_user_id)
                        where osd.deleted_at is null and vos.created_user_id = ".$vendor_id." and osd.status = 1  and os.deleted_at is null and osd.deleted_at is null";
            if(! empty($od_cat_id)){
                $query .= " and osd.od_cat_id =".$od_cat_id;
            }
            $od_service = $this->db->query($query)->result_array();
            if (! empty($od_service)) {
                foreach ($od_service as $key => $s) {
                    $od_service[$key]['image'] = base_url() . 'uploads/od_service_image/od_service_' . $s['od_service_id'] . '.jpg';
                    $od_service[$key]['service_timings'] = $this->db->query("SELECT id, start_time, end_time FROM services_timings where ref_id = ".$s['id']." and service_type = 2 and deleted_at is null;")->result_array();
                }
            }
            $this->set_response_simple($od_service, 'My On Demand Service list', REST_Controller::HTTP_OK, TRUE);
        } else {
            $od_service = $this->od_service_details_model->fields('id, od_service_id, od_cat_id, name, desc, service_duration, price, discount, status')
            ->with_od_category('fields: id, name, desc')
            ->with_service_timings('fields: id, start_time, end_time, ref_id, service_type')
            ->where('id', $target)
            ->get();
            
            $od_service['service_timings'] = $this->db->query("SELECT id, start_time, end_time FROM services_timings where ref_id = ".$od_service['id']." and service_type = 2 and deleted_at is null;")->result_array();
            if(empty($od_service['service_timings'])){
                $od_service['service_timings'] = [];
            }
            $od_service['image'] = base_url() . 'uploads/od_service_image/od_service_' . $od_service['od_service_id'] . '.jpg';
            if (! empty($od_service['od_category'])) {
                $od_service['od_category']['image'] = base_url() . 'uploads/od_category_image/od_category_' . $od_service['od_cat_id'] . '.jpg';
            }
            $this->set_response_simple($od_service, 'On Demand Service', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * To manage bookings
     *
     * @access bookings, booking_items table
     * @author Mehar
     *
     * @param string $type
     * @param integer $target
     */
    public function booking_post($type = 'r', $target = 0){
		
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->booking_model->user_id = $token_data->id;
        $this->booking_item_model->user_id = $token_data->id;
        if($type == 'c'){
            $this->form_validation->set_rules($this->booking_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $booking_track_id = rand();
                $booking_id = $this->booking_model->insert([
                    "track_id" => $booking_track_id,
                    "otp" => rand(999,9999),
                    "vendor_id" => $this->input->post('vendor_id'),
                    "payment_method_id" => $this->input->post('payment_method_id'),
                    "sub_total" => $this->input->post('sub_total'),
                    "promo_id" => $this->input->post('promo_id'),
                    "promo_discount" => $this->input->post('promo_discount'),
                    "discount" => $this->input->post('discount'),
                    "tax" => $this->input->post('tax'),
                    "used_wallet_amount" => $this->input->post('used_wallet_amount'),
                    "total" => $this->input->post('total'),
                    "booking_status" => 1
                ]);
                $services = $this->input->post('services');
                if(! empty($booking_id) && ! empty($services)){
                    foreach($services as $service){
                        $this->booking_item_model->insert([
                            "booking_id" => $booking_id,
                            "service_id" => $service['service_id'],
                            "service_item_id" => $service['service_item_id'],
                            "price" => (empty($service['price']))? NULL: $service['price'],
                            "qty" => (empty($service['qty']))? NULL: $service['qty'],
                            "discount" => (empty($service['discount']))? NULL: $service['discount'],
                            "total" => (empty($service['total']))? NULL: $service['total'],
                            "booking_date" => (empty($service['booking_date']))? NULL: $service['booking_date'],
                            "service_timing_id" => (empty($service['service_timing_id']))? NULL: $service['service_timing_id']
                        ]);
                        $this->booking_model->update([
                            'id'=> $booking_id,
                            "service_id" => $service['service_id']
                        ], 'id');
                    }
                }
                $this->send_notification($this->input->post('vendor_id'), VENDOR_APP_CODE, "Booking status", "New Booking(id:".$booking_track_id.") is received.",['booking_id' => $booking_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'BK'])->get()]);
                $this->set_response_simple($booking_id, 'Service booked..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        }elseif($type == 'vendor_bookings'){
            if(! empty($target)){
                $bookings = $this->booking_model->fields('id, track_id, vendor_id, otp, payment_method_id, sub_total, promo_id, promo_discount, discount, tax, used_wallet_amount, total, response_message, booking_status, created_at, created_user_id ')
                ->with_booking_items('fields: id, booking_id, service_id, service_item_id, price, qty, discount, total, booking_date, service_timing_id')
                ->order_by('id', 'desc')->where('id', $target)->get();
                if(! empty($bookings['booking_items'])){foreach ($bookings['booking_items'] as $key => $booking_item){
                    if($booking_item['service_id'] == 11){
                        $bookings['booking_items'][$key]['service_item'] = (empty($booking_item['service_item_id']))? NULL: $this->hosp_doctor_details_model->where('id', $booking_item['service_item_id'])->fields('id, hosp_doctor_id, name, desc')->get();
                        $bookings['booking_items'][$key]['service_item']['image'] = (empty($bookings['booking_items'][$key]['service_item']))? NULL : base_url() . 'uploads/doctors_image/doctors_' . $bookings['booking_items'][$key]['service_item']['hosp_doctor_id'] . '.jpg';
                    }else{
                        $bookings['booking_items'][$key]['service_item'] = (empty($booking_item['service_item_id']))? NULL: $this->od_service_details_model->where('id', $booking_item['service_item_id'])->fields('id, od_service_id, name,  desc')->get();
                        $bookings['booking_items'][$key]['service_item']['image'] = (empty($bookings['booking_items'][$key]['service_item']))? NULL : base_url() . 'uploads/od_service_image/od_service_' . $bookings['booking_items'][$key]['service_item']['od_service_id'] . '.jpg';
                    }
                    $bookings['booking_items'][$key]['service_timings'] = (empty($booking_item['service_timing_id']))? NULL: $this->service_timings_model->where('id', $booking_item['service_timing_id'])->fields('start_time, end_time')->get();
                }}
                $this->set_response_simple($bookings, 'List of bookings..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $bookings = $this->booking_model->fields('id, track_id, sub_total, discount, tax, total, response_message, booking_status, created_at, created_user_id')
                ->with_booking_items('fields: id, booking_id, service_id', 'where: service_id = '. $this->input->post('service_id'))
                ->order_by('id', 'desc')->where('vendor_id', $token_data->id)->get_all();
                $bookings = ! empty($bookings)? array_values($bookings):[];
                $this->set_response_simple($bookings, 'List of bookings..!', REST_Controller::HTTP_OK, TRUE);
            }
            
        }elseif ($type == 'booking_history'){
            if(! empty($target)){
                $bookings = $this->booking_model->fields('id, track_id, vendor_id, otp, payment_method_id, sub_total, promo_id, promo_discount, discount, tax, used_wallet_amount, total, response_message, booking_status, created_at, created_user_id')
                ->with_booking_items('fields: id, booking_id, service_id, service_item_id, price, qty, discount, total, booking_date, service_timing_id')
                ->order_by('id', 'desc')->where('id', $target)->get();
                if(! empty($bookings['booking_items'])){foreach ($bookings['booking_items'] as $key => $booking_item){
                    if($booking_item['service_id'] == 11){
                        $bookings['booking_items'][$key]['service_item'] = (empty($booking_item['service_item_id']))? NULL: $this->hosp_doctor_details_model->where('id', $booking_item['service_item_id'])->fields('id, hosp_doctor_id, name, desc')->get();
                        $bookings['booking_items'][$key]['service_item']['image'] = (empty($bookings['booking_items'][$key]['service_item']))? NULL : base_url() . 'uploads/doctors_image/doctors_' . $bookings['booking_items'][$key]['service_item']['hosp_doctor_id'] . '.jpg';
                    }else{
                        $bookings['booking_items'][$key]['service_item'] = (empty($booking_item['service_item_id']))? NULL: $this->od_service_details_model->where('id', $booking_item['service_item_id'])->fields('id, od_service_id, name,  desc')->get();
                        $bookings['booking_items'][$key]['service_item']['image'] = (empty($bookings['booking_items'][$key]['service_item']))? NULL : base_url() . 'uploads/od_service_image/od_service_' . $bookings['booking_items'][$key]['od_service_id'] . '.jpg';
                    }
                    $bookings['booking_items'][$key]['service_timings'] = (empty($booking_item['service_timing_id']))? NULL: $this->service_timings_model->where('id', $booking_item['service_timing_id'])->fields('start_time, end_time')->get();
                }}
                $this->set_response_simple($bookings, 'List of bookings..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $bookings = $this->booking_model->fields('id, track_id, sub_total, discount, tax, total, response_message, booking_status, created_at, created_user_id')
                ->with_booking_items('fields: id, booking_id, service_id', 'where: service_id = '. $this->input->post('service_id'))
                ->order_by('id', 'desc')->where('created_user_id', $token_data->id)->get_all();
                $bookings = ! empty($bookings)? array_values($bookings):[];
                $this->set_response_simple($bookings, 'List of bookings..!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'accept'){
            $status = $this->booking_model->update([
                'id' => $this->input->post('id'),
                'booking_status' => 2
            ], 'id');
            if($status){
                $this->send_notification($this->input->post('created_user_id'), USER_APP_CODE, "Booking status", "Your Booking(id:".$this->input->post('track_id').") is Accepted.",['booking_id' => $this->input->post('id'), 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'BK'])->get()]);
                $this->set_response_simple(NULL, 'Booking Accepted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Internal error occured, please check the input', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'reject'){
            $status = $this->booking_model->update([
                'id' => $this->input->post('id'),
                'booking_status' => 5,
                'response_message' => $this->input->post('response_message'),
            ], 'id');
            if($status){
                $this->send_notification($this->input->post('created_user_id'), USER_APP_CODE, "Booking status", "Your Booking(id:".$this->input->post('track_id').") is rejected.",['booking_id' => $this->input->post('id'), 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'BK'])->get()]);
                $this->set_response_simple(NULL, 'Booking Rejected..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Internal error occured, please check the input', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'completed'){
            $status = $this->booking_model->update([
                'id' => $this->input->post('id'),
                'booking_status' => 4,
            ], 'id');
            if($status){
                $this->send_notification($this->input->post('created_user_id'), USER_APP_CODE, "Booking status", "Your Booking(id:".$this->input->post('track_id').") is completed.",['booking_id' => $this->input->post('id'), 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'BK'])->get()]);
                $this->set_response_simple(NULL, 'Booking Completed..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Internal error occured, please check the input', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }
    
    /**
     * @author Mehar
     * @desc bookings dashboar counts
     * 
     *@param integer $service_id
     */
    public function bookings_dashboard_get($service_id = NULL){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if($service_id  == 11){
            $data['active_count'] = $this->hosp_doctor_details_model->where('status', 1)->where('created_user_id', $token_data->id)->count_rows();
            $data['in_active_count'] = $this->hosp_doctor_details_model->where('status', 2)->where('created_user_id', $token_data->id)->count_rows();
        }else{
            $data['active_count'] = $this->od_service_details_model->where('status', 1)->where('created_user_id', $token_data->id)->count_rows();
            $data['in_active_count'] = $this->od_service_details_model->where('status', 2)->where('created_user_id', $token_data->id)->count_rows();
        }
        $data['pending_bookings_count'] = $this->booking_model->where('booking_status', 1)->where('vendor_id', $token_data->id)->count_rows();
        $data['accepted_bookings_count'] = $this->booking_model->where('booking_status', 2)->where('vendor_id', $token_data->id)->count_rows();
        $this->set_response_simple($data, 'dashboard statistics', REST_Controller::HTTP_OK, TRUE);
    }
    
  /**
     * To get list of specialities
     *
     * @author Trupti
     * @param string $target
     */
    public function specialities_get($target = '')
    {
        if (empty($target)) {
            $data = $this->hosp_speciality_model->order_by('id','DESC')->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/speciality_image/speciality_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->hosp_speciality_model->where('id', $target)->get();
            $data['image'] = base_url() . 'uploads/speciality_image/speciality_' . $data['id'] . '.jpg';
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
      /**
     * To get list of specialities
     *
     * @author Trupti
     * @param string $target
     */
    public function od_categories_get($target = '')
    {
        if (empty($target)) {
            $data = $this->od_category_model->order_by('id','DESC')->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/od_category_image/od_category_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->od_category_model->where('id', $target)->get();
            $data['image'] = base_url() . 'uploads/od_category_image/od_category_' . $data['id'] . '.jpg';
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }


}
