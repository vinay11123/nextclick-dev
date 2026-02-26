$input_data = array(
                    'sub_cat_id' => $this->input->post('shop_by_cat_id'),
                    'menu_id' => $this->input->post('menu_id'),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'discount' => $this->input->post('discount'),
                    'status' => $this->input->post('status')
                );
                $item_id = $this->input->post('id');
                $item = $this->food_item_model->where('id', $this->input->post('id'))->get();
                $s = 0;
                if ($this->ion_auth->in_group('admin', $token_data->id) || $item['created_user_id'] == $token_data->id) {
                    $s = 1;
                } else {
                    $cou = $this->db->get_where('deleted_items', array(
                        'vendor_id' => $token_data->id,
                        'item_id' => $item_id
                    ))->num_rows();
                    if ($cou > 0) {
                        $s = 1;
                    } else {
                        $s = 2;
                    }
                }

                if ($s == 1) {
                    $this->food_item_model->update($input_data, $item_id);
                } elseif ($s == 2) {
                    $this->db->insert('deleted_items', array(
                        'vendor_id' => $token_data->id,
                        'item_id' => $item_id,
                        'deleted_at' => date('Y-m-d h:i:s')
                    ));
                    $old = $item_id;
                    $input_data['approval_status'] = ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 2;
                    $input_data['status'] = ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 2;
                    $item_id = $this->food_item_model->insert($input_data);
                    copy('uploads/food_item_image/food_item_' . $old . '.jpg', 'uploads/food_item_image/food_item_' . $item_id . '.jpg');
                }

                if (! empty($this->input->post('image'))) {
                    if (! file_exists('uploads/' . 'food_item' . '_image/')) {
                        mkdir('uploads/' . 'food_item' . '_image/', 0777, true);
                    }
                    if (! file_exists(base_url() ."uploads/food_item_image/food_item_" . $this->input->post('id') . ".jpg")) {
                        unlink(base_url() ."uploads/food_item_image/food_item_" . $this->input->post('id') . ".jpg");
                    }
                    file_put_contents("./uploads/food_item_image/food_item_" . $this->input->post('id') . ".jpg".'?'.time(), base64_decode($this->input->post('image')));
                }
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }