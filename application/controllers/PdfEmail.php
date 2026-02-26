<?php
require_once FCPATH . 'vendor/autoload.php';
use Dompdf\Dompdf;


class PdfEmail extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('setting_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_model');
    }
    public function index()
    {

        $vendor = $this->vendor_list_model->where('vendor_user_id', 2057)->get();

        $this->db->select("v.*, u.phone as vendor_phone, CONCAT_WS(' ', u.first_name, u.last_name) as vendor_name, co.name AS con_name,dst.name AS dist_name, CONCAT_WS(', ', vad.location, vad.line1, st.name, dst.name, vad.zip_code) AS vendor_address");
        $this->db->from('vendors_list v');
        $this->db->join('users u', 'u.id = v.vendor_user_id');
        $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
        $this->db->join('states as st', 'vad.state = st.id');
        $this->db->join('constituencies as co', 'vad.constituency = co.id');
        $this->db->join('districts as dst', 'vad.district = dst.id');
        $this->db->where('v.vendor_user_id', 2057);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();

        $vendor_data = $query->result();
    

        $vendor_data_email = $this->user_model->where([
            'id' => $vendor['vendor_user_id']
        ])->get();

        $user_signature = $this->setting_model->get_user_signature();

        $agreement_id = $this->input->post('agreement_id');

        // if ($vendor['vendor_user_id'] && $agreement_id) {
        //     if (empty($vendor['agreement_id'])) {

                $dompdf = new Dompdf();
                $vendor_image_path = $_SERVER["DOCUMENT_ROOT"] . 'uploads/list_banner_image/list_banner_' . $vendor['vendor_user_id'] . ".jpg";
                if (file_exists($vendor_image_path)) {
                    $vendor_image = $_SERVER["DOCUMENT_ROOT"] . "/uploads/list_banner_image/list_banner_" . $vendor['vendor_user_id'] . ".jpg";
                    $image_path = $this->imageToBase64($vendor_image);
                } else {
                    $vendor_image = '';
                    $image_path = '';
                }

                $vendor_sign = '';
                $signatureFilePath = base_url('uploads/admin/' . $user_signature);
                if ($user_signature != '') {
                    $signatureFilePathVal = $this->imageToBase64($signatureFilePath);
                } else {
                    $signatureFilePathVal = '';
                }


                $data = [
                    'imageSrc1' => $this->imageToBase64('https://nextclick.in/email_images/agr_top.jpg'),
                    'imageSrc2' => $this->imageToBase64('https://nextclick.in/email_images/agr_toptab_bg.jpg'),
                    'imageSrc3' => $this->imageToBase64('https://nextclick.in/email_images/agr_logo.png'),
                    'imageSrc4' => $this->imageToBase64('https://nextclick.in/email_images/agr_footer_bg.jpg'),
                    'imageSrc5' => $this->imageToBase64('https://nextclick.in/email_images/agr_footer.jpg'),
                    'imageSrc6' => $vendor_sign,
                    'imageSrc7' => $signatureFilePathVal,
                    'imageSrc8' => $image_path,

                    'vendor_address' => $vendor_data[0]->vendor_address,
                    'vendor_phone' => $vendor_data[0]->vendor_phone,
                    'vendor_id' => $vendor_data[0]->vendor_user_id,
                    'vendor_email' => $vendor_data_email['email'],
                    'vendor_business_name' => $vendor_data[0]->business_name,
                    'vendor_name' => $vendor_data[0]->vendor_name,
                    'con_name' => $vendor_data[0]->con_name,
                    'dist_name' => $vendor_data[0]->dist_name,

                ];

                $html = $this->load->view('static_agreement_page', $data, true);
                echo $html;
                exit;

                $dompdf->loadHtml($html);
                $dompdf->render();

                $pdfFilename = 'static_agreement_' . uniqid() . '.pdf';
                $pdfFilePath = FCPATH . 'assets/vendor_agreement_pdfs/' . $pdfFilename;

                file_put_contents($pdfFilePath, $dompdf->output());
        //     }
        // }
    }

    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


}
