<?php

class User_group_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users_groups';
        $this->primary_key = 'id';

        $this->_config();
        $this->_form();
        $this->_relations();
    }

    public function _config()
    {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {
    }

    public function _form()
    {
    }

    public function isApprovalPending($userID, $intent)
    {
        try {
            $this->load->model('group_model');
            $group = $this->group_model->groupByName($intent);
            $userGroup = $this->where([
                'user_id' => $userID,
                'group_id' => $group['data']['id']
            ])->get();
            if (empty($userGroup)) {
                $saveObj = [
                    'user_id' => $userID,
                    'group_id' => $group['data']['id'],
                    'status' => 3
                ];
                $this->insert($saveObj);
                $userGroup['status'] = 3;
            }
            return [
                'success' => true,
                'status' => $userGroup['status']
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }

    public function updateToDocsSubmitted($userID, $intent)
    {
        try {
            $this->load->model('group_model');
            $group = $this->group_model->groupByName($intent);
            $userGroup = $this->where([
                'user_id' => $userID,
                'group_id' => $group['data']['id']
            ])->get();
            if ($userGroup['status'] == 3) {
                $this->update([
                    'status' => 4
                ], $userGroup['id']);
            }
            return [
                'success' => true
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }

    public function approveGroup($userID, $intent)
    {
        try {
            $this->load->model('group_model');
            $group = $this->group_model->groupByName($intent);
            $userGroup = $this->where([
                'user_id' => $userID,
                'group_id' => $group['data']['id']
            ])->get();
            $this->update([
                'status' => 1
            ], $userGroup['id']);
            return [
                'success' => true
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }

    public function disApproveGroup($userID, $intent)
    {
        try {
            $this->load->model('group_model');
            $group = $this->group_model->groupByName($intent);
            $userGroup = $this->where([
                'user_id' => $userID,
                'group_id' => $group['data']['id']
            ])->get();
            $this->update([
                'status' => 2
            ], $userGroup['id']);
            return [
                'success' => true
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }
}
