/**
 * open url with authentication
 *
 * $url		string	URL withoud http, https or port
 * $port	integer	Comunication port, default 80
 * $proto	string	Comunication protocol like http, https, rtsp, etc... default http
 * $data	array	An array with all variables to send, example: array('variable_name' => 'value')
 * $method	string	POST, PUT, GET, DELETE, etc...
 * $header	array	An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
 * $user	string	The user name to use in authentication
 * $pass	string	The password to use in authentication
 *
 */
function openURL($url, $port=80, $proto='http', $data=false, $method=false, $header='' $user='', $pass='')
{
	$curl = curl_init();

    if ($proto=='http' && $port==80) {
        $url='http://'.$host;
    } elseif ($proto=='https' && $port==443) {
        $url='https://'.$host;
    } else {
        $url=$proto.'://'.$host.':'.$port;
    }

	switch ($method){
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			break;
		default:
			if ($data) {
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($curl, CURLOPT_POST, false);
				$url = sprintf("%s?%s", $url, http_build_query($data));
			}
	}

	curl_setopt($curl, CURLOPT_USERAGENT,		'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');
	curl_setopt($curl, CURLOPT_COOKIEFILE,		"cookie.txt");	// set cookie file
	curl_setopt($curl, CURLOPT_COOKIEJAR,		"cookie.txt");	// set cookie jar
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,	true);			// return web page
	curl_setopt($curl, CURLOPT_HEADER,			false);			// don't return headers
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION,	true);			// follow redirects
	curl_setopt($curl, CURLOPT_ENCODING,		"");			// handle all encodings
	curl_setopt($curl, CURLOPT_AUTOREFERER,		true);			// set referer on redirect
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,	120);			// timeout on connect
	curl_setopt($curl, CURLOPT_TIMEOUT,			120);			// timeout on response
	curl_setopt($curl, CURLOPT_MAXREDIRS,		10);			// stop after 10 redirects
	curl_setopt($curl, CURLOPT_HTTPAUTH			CURLAUTH_BASIC);// HTTP authentication method
	curl_setopt($curl, CURLOPT_URL,				$url);			// URL
	curl_setopt($curl, CURLOPT_PORT,			$port);			// Web port

	if ($user!='' && !is_null($user)) {
		if ($pass!='' && !is_null($pass)) {
			curl_setopt($curl, CURLOPT_USERPWD, "$user:$pass");	// Username & Password
		} else {
			curl_setopt($curl, CURLOPT_USERNAME, "$user");
		}
	}

	if (is_array($header) && !empty($header)
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

	$content	= curl_exec($curl);
	$err		= curl_errno($curl);
	$errmsg		= curl_error($curl);
	$getinfo	= curl_getinfo($curl);
	curl_close($curl);

	$getinfo['errno']	= $err;
	$getinfo['errmsg']	= $errmsg;
	$getinfo['content']	= $content;
	return $getinfo;
}

