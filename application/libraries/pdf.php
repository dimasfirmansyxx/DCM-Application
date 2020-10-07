<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Pdf extends Dompdf{

    protected function ci()
    {
        return get_instance();
    }

    public function load_view($view, $data = array()){
        $html = $this->ci()->load->view($view, $data, TRUE);
        $this->load_html($html);
        $this->render();
		$this->stream($data['filename'] . ".pdf", array("Attachment" => false));
    }
}