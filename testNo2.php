<?php  
	function apiRequest ($method, $url, $data){
			$ci = curl_init();
			switch ($method){
		      case "POST":
		         curl_setopt($ci, CURLOPT_POST, 1);
		         if ($data)
		            curl_setopt($ci, CURLOPT_POSTFIELDS, json_encode($data));
		         break;
		      case "PUT":
		         curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "PUT");
		         if ($data)
		            curl_setopt($ci, CURLOPT_POSTFIELDS, json_encode($data));			 					
		         break;
		     case "DELETE":
		         curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "DELETE");
		         if ($data)
		            curl_setopt($ci, CURLOPT_POSTFIELDS, json_encode($data));			 					
		         break;   
		      default:
		         if ($data)
		            $url = sprintf("%s?%s", $url, http_build_query($data));
		   }

	        curl_setopt($ci, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ci, CURLOPT_HEADER, FALSE);
	        curl_setopt($ci, CURLOPT_URL, $url);
	        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT ,0);
	        curl_setopt($ci, CURLOPT_TIMEOUT, 400);

	        $hasil = curl_exec($ci);
	        curl_close($ci);

	        if ($hasil) {
	            $json_hasil = json_decode(utf8_encode($hasil),TRUE);
	            return $json_hasil;
	        } else {
	        	return "Request Failed.";
	        }
		}
	$jumlah=0;
	$tamp=0;
	$url = 'https://www.idx.co.id/umbraco/Surface/ListedCompany/GetTradingInfoSS?code=CPIN&length=100';
    $hasil = apiRequest('GET',$url,null);
    $val1=0;
    $val2=0;
    $val3=0;
    $val4=0;
    $val5=0;
    $senin=0;
    $selasa=0;
    $rabu=0;
    $kamis=0;
    $jumat=0;
    $sabtu=0;
    foreach ($hasil['replies'] as $data2) { 
		 $date=$data2['Date']; 
		 $date=substr($date,0,10 );
		 $hari = date("l", strtotime($date));
		 if($hari=="Monday"){
		 	$val1+=$data2['Close'];
		 	$senin++;
		 	$hari="Senin";
		 }else if($hari=="Tuesday"){
		 	$val2+=$data2['Close'];
		 	$selasa++;
		 	$hari="Selasa";
		 }else if($hari=="Wednesday"){
		 	$val3+=$data2['Close'];
		 	$rabu++;
		 	$hari="Rabu";
		 }else if($hari=="Thursday"){
		 	$val4+=$data2['Close'];
		 	$kamis++;
		 	$hari="Kamis";
		 }else if($hari=="Friday"){
		 	$val5+=$data2['Close'];
		 	$jumat++;
		 	$hari="Jumat";
		 }		
    }
    $hasil = array(	'Senin' => number_format(($val1/$senin),2) ,
    				'Selasa'=> number_format(($val2/$selasa),2),
    				'Rabu'=> number_format(($val3/$rabu),2),
    				'Kamis'=> number_format(($val4/$kamis),2),
    				'Jumat'=> number_format(($val5/$jumat),2)

     );
    $hasil_json=json_encode($hasil);
    echo ($hasil_json);
?>