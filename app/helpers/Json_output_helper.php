<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	function json_output($statusHeader, $response)
	{
		/*$ci =& get_instance();
		$ci->output->set_content_type('application/json');
		$ci->output->set_status_header($statusHeader);
		$ci->output->set_output(json_encode($response));*/

        $ci =& get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_status_header($statusHeader)
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();

        exit;
	}





