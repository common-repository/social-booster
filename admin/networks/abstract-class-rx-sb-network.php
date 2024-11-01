<?php


abstract class Rx_Sb_Network{
    /**
     * User Connected or not connected
     */
    public function sb_update_connection_status($id, $status) {

      global $wpdb;
      $network_table = $wpdb->prefix . 'sb_networks';
      $wpdb->update($network_table, array('auth_con'=>$status), array('id'=>$id));
      if ($wpdb->last_error !== '') {
          return('Failed');
      }
      else {
        return('Success');
      }
    }

    /**
     * User status togglebar
     */
    public function sb_update_inplugin_status($id, $status) {

      global $wpdb;
      $network_table = $wpdb->prefix . 'sb_networks';
      $wpdb->update($network_table, array('auth_status'=>$status), array('id'=>$id));
      if ($wpdb->last_error !== '') {
          return('Failed');
      }
      else {
        return('Success');
      }
    }

    /**
     * Network Authorization date
     */
    public function sb_register_date() {
        $date = date('Y-m-d',strtotime('now'));
        return $date;
    }


    public function save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type = 'instant', $success = true, $error = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'sb_shared_posts';
        $wpdb->insert(
            $table,
            array(
                'post_id' => $post_id,
                'published_date' => $published_date,
                'post_meta' => serialize($data_array),
                'profile_id' => $profile_id,
                'network_id' => $network_id,
                'share_type' => $share_type,
                'success' => $success,
                'error_msg' => $error,
            )
        );
    }


		public function rx_sb_make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
		{
			//create the URL
			$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

			//get the url
			//could also use cURL here
			$response = file_get_contents($bitly);

			//parse depending on desired format
			if(strtolower($format) == 'json')
			{
				$json = @json_decode($response,true);
				return $json['results'][$url]['shortUrl'];
			}
			else //xml
			{
				$xml = simplexml_load_string($response);
				return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
			}
		}

    /**
     * Tiny url setup
     */
    public function rx_sb_get_tiny_url($url)  {
    	$ch = curl_init();
    	$timeout = 5;
    	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    	$data = curl_exec($ch);
    	curl_close($ch);
    	return $data;
    }

    /**
    * isgd url shorten
    */
    public function rx_sb_isgdShorten($url,$shorturl = null)
    {
        $url = urlencode($url);
        $basepath = "https://is.gd/create.php?format=simple";

        $result = array();
        $result["errorCode"] = -1;
        $result["shortURL"] = null;
        $result["errorMessage"] = null;

        $opts = array("http" => array("ignore_errors" => true));
        $context = stream_context_create($opts);

        if($shorturl)
            $path = $basepath."&shorturl=$shorturl&url=$url";
        else
            $path = $basepath."&url=$url";

        $response = @file_get_contents($path,false,$context);

        if(!isset($http_response_header))
        {
            $result["errorMessage"] = "Local error: Failed to fetch API page";
            return($result);
        }

        //Hacky way of getting the HTTP status code from the response headers
        if (!preg_match("{[0-9]{3}}",$http_response_header[0],$httpStatus))
        {
            $result["errorMessage"] = "Local error: Failed to extract HTTP status from result request";
            return($result);
        }

        $errorCode = -1;
        switch($httpStatus[0])
        {
            case 200:
                $errorCode = 0;
                break;
            case 400:
                $errorCode = 1;
                break;
            case 406:
                $errorCode = 2;
                break;
            case 502:
                $errorCode = 3;
                break;
            case 503:
                $errorCode = 4;
                break;
        }

        if($errorCode==-1)
        {
            $result["errorMessage"] = "Local error: Unexpected response code received from server";
            return($result);
        }

        $result["errorCode"] = $errorCode;
        if($errorCode==0)
            $result["shortURL"] = $response;
        else
            $result["errorMessage"] = $response;

        return($result);
    }
}
