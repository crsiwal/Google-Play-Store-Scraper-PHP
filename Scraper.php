<?php
ini_set('max_execution_time', 0);

function get_app_details($url) {
	$html = get_html($url);
	$iserror = sub_elements($html['content'], 'error-section', true, false, true);
	if (strpos($iserror, 'URL was not found') !== false) {
		return false;
	} else {
		return get_content($url, $html['content']);
	}
}

function get_html($url) {
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
	$options = array(
		CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
		CURLOPT_POST           => false,        //set to GET
		CURLOPT_USERAGENT      => $user_agent, //set user agent
		CURLOPT_COOKIEFILE     => "cookie.txt", //set cookie file
		CURLOPT_COOKIEJAR      => "cookie.txt", //set cookie jar
		CURLOPT_RETURNTRANSFER => true,     // return web page
		CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		CURLOPT_ENCODING       => "",       // handle all encodings
		CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		CURLOPT_TIMEOUT        => 120,      // timeout on response
		CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	);
	$ch      = curl_init($url);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err     = curl_errno($ch);
	$errmsg  = curl_error($ch);
	$header  = curl_getinfo($ch);
	curl_close($ch);
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $header;
}

function get_content($url, $html) {
	$content = array(
		"url" => $url,
		"appname" => trim(sub_elements($html, "AHFaub")),
		"rating" => trim(sub_elements($html, "BHMmbe"))
	);

	$data = sub_elements($html, "R8zArc", false);
	$content['developer'] = trim(sub_elements($data[0], "hrTbp"));
	$content['subcategory'] = trim(sub_elements($data[1], "hrTbp"));
	$content['ratingcount'] = str_replace(" total", "", sub_elements($html, "EymY4b"));
	$cont = sub_elements($html, "hAyfc", false);
	$content['updated'] = (is_array($cont) && isset($cont[0])) ? sub_elements($cont[0], "htlgb") : 'Unknown';
	$content['size'] = (is_array($cont) && isset($cont[1])) ? sub_elements($cont[1], "htlgb") : 'Unknown';
	$content['installs'] = (is_array($cont) && isset($cont[2])) ? sub_elements($cont[2], "htlgb") : 'Unknown';
	$content['version'] = (is_array($cont) && isset($cont[3])) ? sub_elements($cont[3], "htlgb") : 'Unknown';
	$content['androidneed'] = (is_array($cont) && isset($cont[4])) ? sub_elements($cont[4], "htlgb") : 'Unknown';
	$content['contentrating'] = (is_array($cont) && isset($cont[5])) ? sub_elements($cont[5], "htlgb") : 'Unknown';
	$content['contentrating'] = str_replace("Rated for ", "", $content['contentrating']);
	$content['contentrating'] = str_replace("Learn More", "", $content['contentrating']);
	$content['contactemail'] = (is_array($cont) && isset($cont[9])) ? sub_elements($cont[9], "euBY6b") : '';
	return $content;
}

function sub_elements($html, $id, $singleNode = true, $bytagname = false, $byid = false) {
	$identifier = ($byid == true) ? "(@id, '" . $id . "')" : "(@class, '" . $id . "')";
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
	$node = $finder->query("//*[contains" . $identifier . "]");
	if ($singleNode == true) {
		if ($bytagname == true) {
			$node = $doc->getElementsByTagName($id);
			$html = $doc->saveHTML($node->item(0));
			$html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);
			return strip_tags($html);
		} else {
			$html = $doc->saveHTML($node->item(0));
			$html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);
			$html = preg_replace('/\s+/', ' ', $html);
			$html = strip_tags($html);
			return (strlen($html) > 250) ? "" : $html;
		}
	} else {
		if ($bytagname == true) {
			$array = array();
			$list = $doc->getElementsByTagName($id);
			foreach ($list as $row)
				$array[] = $doc->saveHTML($row);
			return $array;
		} else {
			$array = array();
			foreach ($node as $row)
				$array[] = $doc->saveHTML($row);
			return $array;
		}
	}
}

function output_file($path, $append = true, $content = "") {
	if ($append) {
		file_put_contents(dirname(__FILE__) . "/" . $path, $content);
	} else {
		file_put_contents(dirname(__FILE__) . "/" . $path, $content, FILE_APPEND);
	}
}

output_file('output.csv', false);
output_file('invalid.txt', false);
$input = fopen(dirname(__FILE__) . '/urls.csv', 'r');
$output = fopen(dirname(__FILE__) . '/output.csv', 'a');
fputcsv(
	$output,
	[
		"Offered By",
		"App Name",
		"Installs",
		"Last Update",
		"Developer email id",
		"Category",
		"Rating",
		"App Size",
		"App Version",
		"Min. Android Version Req.",
		"Playstore URL"
	]
);
$appcount = $invalid_url_count = 0;
while (($line = fgetcsv($input)) !== FALSE) {
	set_time_limit(0);
	$content = get_app_details($line[0]);
	if (!$content) {
		$invalid_url_count++;
		echo "Not available : {$line[0]}" . PHP_EOL;
		$url_line = $line[0] . PHP_EOL;
		output_file('invalid.txt', true, $url_line);
	} else {
		fputcsv($output, [
			$content["developer"],
			$content["appname"],
			$content["installs"],
			$content["updated"],
			$content["contactemail"],
			$content["subcategory"],
			$content["rating"],
			$content["size"],
			$content["version"],
			$content["androidneed"],
			$content["url"],
		]);
		$appcount++;
		echo "Added " . $content["appname"] . PHP_EOL;
	}
}
echo "\nApps Data Found: $appcount apps\n";
echo "\nInvalid URLS $invalid_url_count\n";
fclose($input);
fclose($output);
