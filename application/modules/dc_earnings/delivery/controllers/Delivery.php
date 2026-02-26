<?php

class Delivery extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('vehicle_model');
        $this->load->model('arearate_model');
        $this->load->model('setting_model');
        $this->load->model('delivery_fee_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
    }

    public function vehicle($type = 'r', $id = 0)
    {
 

        if ($type == 'r') {
            $this->data['title'] = 'Delivery List';
            $this->data['content'] = 'delivery/delivery_list';
            $this->data['nav_type'] = 'Delivery';
            $this->data['vehicledata'] = $this->vehicle_model->get_all();
            $this->_render_page($this->template, $this->data);
        }
        
        if ($type == 'c') {


            $this->form_validation->set_rules($this->vehicle_model->rules);
            if ($this->form_validation->run() == FALSE) {
				
                $this->data['title'] = 'Delivery';
                $this->data['content'] = 'delivery/add_delivery';
                $this->data['nav_type'] = 'Delivery';
                $this->data['max_order_weight'] = $this->setting_model->where('key','max_order_weight')->get()['value'];
                $this->_render_page($this->template, $this->data);
            } else {
 
                $is_inserted = $this->vehicle_model->insert([
                    'name' => $this->input->post('vehiclename'),
                    'min_capacity' => $this->input->post('mincapecity'),
                    'max_capacity_end' => $this->input->post('maxcapecity'),
                    'desc' => $this->input->post('description')
                ]);
                redirect('vehicle/r/0', 'refresh');
            }
        } elseif ($type == 'd') {

            $this->vehicle_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->vehicle_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['updelivery'] = $this->vehicle_model->get($id);
				$this->data['max_order_weight'] = $this->setting_model->where('key','max_order_weight')->get()['value'];
                $this->data['title'] = 'Delivery';
                $this->data['content'] = 'delivery/update_delivery';
                $this->data['nav_type'] = 'Delivery';
                $this->_render_page($this->template, $this->data);
            } else {
                $is_updated = $this->vehicle_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('vehiclename'),
                    'min_capacity' => $this->input->post('mincapecity'),
                    'max_capacity_end' => $this->input->post('maxcapecity'),
                    'desc' => $this->input->post('description')
                ], 'id');
                redirect('vehicle/r/0', 'refresh');
            }
        }
    }

    public function delivery_area($type = 'r', $rowno = 0)
    {

        if ($type == 'c') {
            $this->form_validation->set_rules($this->delivery_fee_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Delivery Area';
                $this->data['content'] = 'delivery/add_delivery_area';
                $this->data['nav_type'] = 'Delivery Area';
                $this->data['state'] = $this->state_model->get_all();
                $this->data['vechile'] = $this->vehicle_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                
                if ($this->input->post('district_id') == 'stateall' || $this->input->post('district_id') == '') {
                    $districtid = null;
                    $this->db->where('district_id', $districtid);
                } else {
                    $districtid = $this->input->post('district_id');
                    $this->db->where('district_id', $districtid);
                }

                if ($this->input->post('constituency_id') == 'conall' || $this->input->post('constituency_id') == '') {
                    $constid = null;
                    $this->db->where('constituency_id', $constid);
                } else {
                    $constid = $this->input->post('constituency_id');
                    $this->db->where('constituency_id', $constid);
                }
                
                if ($this->input->post('state_id') == 'conall' || $this->input->post('state_id') == '') {
                    $stateid = null;
                    $this->db->where('state_id', $stateid);
                } else {
                    $stateid = $this->input->post('state_id');
                    $this->db->where('state_id', $stateid);
                }

                $data1 = $this->delivery_fee_model->where('vehicle_type_id', $this->input->post('vehicle_type_id'))->get_all();

                
                if (! $data1) {
                    $id = $this->delivery_fee_model->insert([
                        'state_id' => $stateid,
                        'district_id' => $districtid,
                        'constituency_id' => $constid,
                        'vehicle_type_id' => $this->input->post('vehicle_type_id'),
                        'flat_rate' => $this->input->post('rlatrate'),
                        'flat_distance' => $this->input->post('flatdistance'),
                        'per_km' => $this->input->post('per_km'),
                        'vendor_to_user_max_distance' => $this->input->post('vendor_to_user_max_distance'),
                        'vendor_to_delivery_captain_max_distance' => $this->input->post('vendor_to_delivery_captain_max_distance'),
                        'nc_flat_rate' => $this->input->post('nc_flat_rate'),
                        'nc_per_km' => $this->input->post('nc_per_km'),
                    ]);
                }else{
                    echo "<script>alert('Record is already existed.')</script>";
                }
                redirect('delivery_area/r/0', 'refresh');
            }
        }
        if ($type == 'r') {
            $this->data['title'] = 'Delivery Area List';
            $this->data['content'] = 'delivery/delivery_area_list';
            $this->data['nav_type'] = 'Delivery Area';
            $this->data['arearate'] = $this->delivery_fee_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
        if ($type == 'u') {

            $this->form_validation->set_rules($this->delivery_fee_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'update Delivery Area';
                $this->data['content'] = 'delivery/update_delivery_area';
                $this->data['nav_type'] = 'Delivery Area';
                $this->data['updatearea'] = $this->delivery_fee_model->get($rowno);

                $this->data['state'] = $this->state_model->get_all();
                $this->data['vechile'] = $this->vehicle_model->get_all();
 
                $this->_render_page($this->template, $this->data);
            } else {

                if ($this->input->post('district_id') == "") {
                    $districtval = "null";
                } else {
                    $districtval = $this->input->post('district_id');
                }

                if ($this->input->post('constituancy_id') == "") {
                    $constituancyval = "null";
                } else {
                    $constituancyval = $this->input->post('constituancy_id');
                }
       

                $is_updated = $this->delivery_fee_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $districtval,
                    'constituency_id' => $constituancyval,
                    'vehicle_type_id' => $this->input->post('vechile'),
                    'flat_rate' => $this->input->post('rlatrate'),
                    'flat_distance' => $this->input->post('flatdistance'),
                    'per_km' => $this->input->post('Perkm'),
                    'vendor_to_user_max_distance' => $this->input->post('vendor_to_user_max_distance'),
                    'vendor_to_delivery_captain_max_distance' => $this->input->post('vendor_to_delivery_captain_max_distance'),
                    'nc_flat_rate' => $this->input->post('nc_flat_rate'),
                    'nc_per_km' => $this->input->post('nc_per_km'),
                ], 'id');

                redirect('delivery_area/r/0', 'refresh');
            }
        }
        if ($type == 'd') {
            $this->delivery_fee_model->delete([
                'id' => $rowno
            ]);
            redirect('delivery_area/r/0', 'refresh');
        }
    }

    public function fetchdisdata()
    {
        $data = $this->district_model->where('state_id', $this->input->post('state_id'))
            ->get_all();
        echo "<option value='stateall'>All</option>";
        foreach ($data as $a) {
            echo "<option value='" . $a['id'] . "'>" . $a['name'] . "</option>";
        }
    }

    public function fetchcondata()
    {
        $data = $this->constituency_model->where('district_id', $this->input->post('district_id'))
            ->get_all();
        echo "<option value='conall'>All</option>";
        foreach ($data as $a) {
            echo "<option value='" . $a['id'] . "'>" . $a['name'] . "</option>";
        }
    }


    
}

