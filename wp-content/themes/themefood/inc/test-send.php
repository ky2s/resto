<?PHP
function sendMessage() {
    $fields = array(
        'app_id' => "f4ff5674-187d-4b23-b71c-e019553ec0c7",
        'included_segments' => array(
            'Subscribed Users'
        ),
        'contents' => array(
			"en" => 'Yeay! Ada order baru dari Wahyu Putra (#125)'
		),
        'web_buttons' => array(
			array(
				"id" => "view-order",
				"text" => "Lihat Orderan",
				"url" => "https://themefood.id"
			)
		)
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic MGQ2MDAyNzgtMGM3ZS00OWQzLThjMWUtYTlmOTNmMTk5ZWFm'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
    $response = curl_exec($ch);
    curl_close($ch);    
    return $response;
}
sendMessage();
?>