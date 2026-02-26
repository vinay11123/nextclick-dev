<?php
require APPPATH . '/vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Ecommerce extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template = 'ecommerce/pickup_orders';

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('pagination');
        $this->load->library('user_agent');
        $this->load->model('Ecommerce_model');
    }

    public function pickup_order_entry()
    {
        $this->data['title'] = 'Pickup Orders';
        $this->template = 'ecommerce/pickup_orders';
        $this->data['nav_type'] = 'pickup_orders';
        $queryResult = $this->Ecommerce_model->get_user_details();

        $this->data['statusResult'] = $queryResult['status_result'];
        $this->data['customerResult'] = $queryResult['customer_result'];
        $this->data['paymentResult'] = $queryResult['payment_result'];
        $this->data['deliveryResult'] = $queryResult['delivery_result'];
        $this->load->view($this->template, $this->data);
    }

    public function pickup_orders($type = 'r')
    {
        if ($type == 'r') {
            $postData = $this->input->post();
            $data = $this->Ecommerce_model->get_pickup_orders_table($postData);
            echo json_encode($data);
        }

        if ($type == 'edit') {

            $this->data['title'] = 'Pickup Orders';
            $this->template = 'ecommerce/pickup_orders_details';
            $this->data['nav_type'] = 'pickup_orders';
            $id = base64_decode(base64_decode($this->input->get('id')));

            $queryResult = $this->Ecommerce_model->get_pickup_orders_table_by_id($id);

            $this->data['orderDetails'] = $queryResult['query_result'];
            $this->data['statusDetails'] = $queryResult['status_result'];
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'pdf') {
            $this->data['title'] = 'Pickup Orders';
            $this->data['nav_type'] = 'pickup_orders';
            $id = base64_decode(base64_decode($this->input->get('id')));
            $ctype = $this->input->get('ctype');

            $queryResult = $this->Ecommerce_model->get_pickup_orders_table_by_id($id);

            $this->data['orderDetails'] = $queryResult['query_result'];

            if (!empty($this->data['orderDetails'])) {
                $dompdf = new Dompdf();


                $data = [
                    'imageSrc3' => $this->imageToBase64('https://nextclick.in/email_images/agr_logo.png'),
                    'user_name' => $this->data['orderDetails'][0]['customer_name'],
                    'user_address' => $this->data['orderDetails'][0]['delivery_address'],
                    'user_mobile' => $this->data['orderDetails'][0]['delivery_phone'],
                    'user_mail' => $this->data['orderDetails'][0]['delivery_email'],
                    'invoice_id' => $this->data['orderDetails'][0]['id'],
                    'track_id' => $this->data['orderDetails'][0]['track_id'],
                    'invoice_date' => $this->data['orderDetails'][0]['created_at'],
                    'order_status' => $this->data['orderDetails'][0]['order_status'],
                    'product_data' => $this->data['orderDetails'],
                    'ctype' => $ctype
                ];


                $html = $this->load->view('pickup_order_details_pdf', $data, true);
                // echo $html;

                $dompdf->loadHtml($html);

                $dompdf->render();

                $canvas = $dompdf->get_canvas();
                $font = $dompdf->getFontMetrics()->get_font("Arial", "normal");
                $canvas->page_text(270, 750, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0, 0, 0));


                $pdfFilename = 'invoice' . time() . '.pdf';
                $pdfFilePath = FCPATH . 'exports/pickup_invoice/' . $pdfFilename;

                if (!file_exists('exports/' . 'pickup_invoice/')) {
                    mkdir('exports/' . 'pickup_invoice/', 0777, true);
                }
                file_put_contents($pdfFilePath, $dompdf->output());
                if ($ctype == 'user') {

                    $message = "";
                    $this->email->clear();
                    $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));

                    if ($ctype == 'user') {
                        $message = 'Dear Customer, Your pickup order invoice is attached below. Thank you.';
                        $this->email->to($data['user_mail']);
                    }

                    $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Invoice');
                    $this->email->message($message);
                    $this->email->attach($pdfFilePath);
                    $this->email->send();
                    $this->email->send();
                } else if ($ctype == 'user_download') {
                    echo json_encode(base_url() . 'exports/pickup_invoice/' . $pdfFilename);
                }
            }
        }
    }

    public function ecom_order_entry()
    {
        $this->data['title'] = 'Ecom Orders';
        $this->template = 'ecommerce/ecom_orders';
        $this->data['nav_type'] = 'ecom_orders';
        $queryResult = $this->Ecommerce_model->get_user_details();

        $this->data['statusResult'] = $queryResult['status_result'];
        $this->data['vendorResult'] = $queryResult['vendor_result'];
        $this->data['customerResult'] = $queryResult['customer_result'];
        $this->data['paymentResult'] = $queryResult['payment_result'];
        $this->data['deliveryResult'] = $queryResult['delivery_result'];
        $this->load->view($this->template, $this->data);
    }

    public function ecom_orders($type = 'r')
    {
        if ($type == 'r') {
            $postData = $this->input->post();
            $data = $this->Ecommerce_model->get_ecom_orders_table($postData);
            echo json_encode($data);
        } else if ($type == 'edit') {

            $this->data['title'] = 'Ecom Orders';
            $this->template = 'ecommerce/ecom_orders_details';
            $this->data['nav_type'] = 'ecom_orders';
            $id = base64_decode(base64_decode($this->input->get('id')));
            $queryResult = $this->Ecommerce_model->get_ecom_orders_table_by_id($id);

            $this->data['orderst'] = $queryResult['query_result'];
            $this->data['custprod'] = $queryResult['status_result'];
            $this->data['order_status_result'] = $queryResult['order_status_result'];

            $this->_render_page($this->template, $this->data);

        } else if ($type == 'pdf') {
            $this->data['title'] = 'Ecom Orders';
            $this->data['nav_type'] = 'ecom_orders';
            $id = base64_decode(base64_decode($this->input->get('id')));
            $ctype = $this->input->get('ctype');

            $queryResult = $this->Ecommerce_model->get_ecom_orders_table_by_id($id);

            $this->data['orderst'] = $queryResult['query_result'];
            $this->data['custprod'] = $queryResult['status_result'];

            $vendor_id = $queryResult['query_result']['0']['vendor_user_id'];
            $vendor_ids = $queryResult['query_result']['0']['vendor_id'];

            $user_email = $queryResult['query_result']['0']['email'];


            $vendor_data = $this->Ecommerce_model->get_vendor_details($vendor_id);
            $vendor_email = $vendor_data[0]->email;

            $this->data['vendor'] = $vendor_data;

            $vendor_data_email = $this->user_model->where([
                'id' => $vendor_id
            ])->get();
            
             $vendor_data_phone = $this->user_model->where([
                'id' => $vendor_id
            ])->get();

            if ($vendor_id) {
               // $dompdf = new Dompdf();

                $data = [
                    'imageSrc3' => $this->imageToBase64('https://nextclick.in/email_images/agr_logo.png'),
                    'vendor_address' => $vendor_data[0]->vendor_address,
                    'vendor_email' => $vendor_data_email['email'],
                    'vendor_phone' => $vendor_data_phone['phone'],
                    'vendor_fssai' => $vendor_data[0]->fssai_number,
                    'vendor_gst' => $vendor_data[0]->gst_number,
                    'vendor_business_name' => $vendor_data[0]->business_name,
                    'con_name' => $vendor_data[0]->con_name,
                    'dist_name' => $vendor_data[0]->dist_name,
                    'user_name' => $this->data['orderst'][0]['first_name'],
                    'user_address' => $this->data['orderst'][0]['address'],
                    'user_mobile' => $this->data['orderst'][0]['phone'],
                    'user_mail' => $this->data['orderst'][0]['email'],
                    'invoice_id' => $this->data['orderst'][0]['id'],
                    'track_id' => $this->data['orderst'][0]['track_id'],
                    'invoice_date' => $this->data['orderst'][0]['created_at'],
                    'product_data' => $this->data['custprod'],
                    'ecom_data' => $this->data['orderst'][0],
                    'ctype' => $ctype,
                    'vendor_id' => $vendor_ids
                ];
                
                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
                $options->set('defaultFont', 'DejaVu Sans');  // 🔥 IMPORTANT
                $options->set('chroot', FCPATH);   // 🔥 THIS IS THE KEY
                
                $dompdf = new Dompdf($options);

                //$html = $this->load->view('ecom_order_details_pdf', $data, true);
                $html = $this->load->view('send_inoive_to_user', $data, true);
                 /*echo '<pre>';var_dump($data);
                 echo $html;  exit;*/

                $dompdf->loadHtml($html);

                $dompdf->render();

                $canvas = $dompdf->get_canvas();
                //$font = $dompdf->getFontMetrics()->get_font("Arial", "normal");
                //$canvas->page_text(270, 750, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0, 0, 0));
                $canvas = $dompdf->get_canvas();
                $font = $dompdf->getFontMetrics()->get_font("DejaVu Sans", "normal");
                
                $canvas->page_text(
                    $canvas->get_width() / 2 - 35,
                    $canvas->get_height() - 25,
                    "Page {PAGE_NUM} of {PAGE_COUNT}",
                    $font,
                    9,
                    array(0,0,0)
                );



                $pdfFilename = 'invoice' . time() . '.pdf';
                $pdfFilePath = FCPATH . 'exports/invoice/' . $pdfFilename;

                if (!file_exists('exports/' . 'invoice/')) {
                    mkdir('exports/' . 'invoice/', 0777, true);
                }
                file_put_contents($pdfFilePath, $dompdf->output());
                if ($ctype == 'user' || $ctype == 'vendor' || $ctype == 'admin') {
                    $message = "";
                    $this->email->clear();
                    $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));

                    if ($ctype == 'user' || $ctype == 'admin') {
                        $message = 'Dear Customer, Your invoice is attached below. Thank you for your purchase.';
                        $this->email->to($user_email);
                    } else if ($ctype == 'vendor') {
                        $message = 'Dear Vendor, Your invoice is attached below. Order has been received. Please proceed with packing';
                        $this->email->to($vendor_data_email['email']);
                    }

                    $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Invoice');
                    $this->email->message($message);
                    $this->email->attach($pdfFilePath);
                    $this->email->send();
                    $this->email->send();
                } else if ($ctype == 'user_download' || $ctype == 'vendor_download') {

                    echo json_encode(base_url() . 'exports/invoice/' . $pdfFilename);
                }
            }
        }
    }


    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


    public function deleteFile()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $filePath = $this->input->post('filePath');
            if (!empty($filePath)) {
                $documentRoot = $_SERVER["DOCUMENT_ROOT"];

                $filePath = str_replace("http://localhost", $documentRoot, $filePath);
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        echo 'File deleted successfully';
                    } else {
                        echo 'Error deleting file';
                    }
                } else {
                    echo 'File not found';
                }
            } else {
                echo 'File path not provided';
            }
        } else {
            echo 'Invalid request method';
        }
    }

}
