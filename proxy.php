<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: proxy.php,v 1.3 2003/09/24 00:41:38 arino Exp $
//

/*
 * http_request($url)
 *   HTTP�ꥯ�����Ȥ�ȯ�Ԥ����ǡ������������
 * $url     : http://����Ϥޤ�URL(http://user:pass@host:port/path?query)
 * $method  : GET, POST, HEAD�Τ����줫(�ǥե���Ȥ�GET)
 * $headers : Ǥ�դ��ɲåإå�
 * $post    : POST�λ�����������ǡ������Ǽ��������('�ѿ�̾'=>'��')
*/

function http_request($url,$method='GET',$headers='',$post=array())
{
	global $use_proxy,$proxy_host,$proxy_port;
	global $need_proxy_auth,$proxy_auth_user,$proxy_auth_pass;
	
	$rc = array();
	$arr = parse_url($url);
	
	$via_proxy = $use_proxy and via_proxy($arr['host']);
	
	// query
	$arr['query'] = isset($arr['query']) ? '?'.$arr['query'] : '';
	// port
	$arr['port'] = isset($arr['port']) ? $arr['port'] : 80;
	
	$url = $via_proxy ? $arr['scheme'].'://'.$arr['host'].':'.$arr['port'] : '';
	$url .= isset($arr['path']) ? $arr['path'] : '/';
	$url .= $arr['query'];
	
	$query = $method.' '.$url." HTTP/1.0\r\n";
	$query .= "Host: ".$arr['host']."\r\n";
	$query .= "User-Agent: PukiWiki/".S_VERSION."\r\n";

	// proxy��Basicǧ��
	if ($need_proxy_auth and isset($proxy_auth_user) and isset($proxy_auth_pass))
	{
		$query .= 'Proxy-Authorization: Basic '.
			base64_encode($proxy_auth_user.':'.$proxy_auth_pass)."\r\n";
	}
	// Basic ǧ����
	if (isset($arr['user']) and isset($arr['pass']))
	{
		$query .= 'Authorization: Basic '.
			base64_encode($arr['user'].':'.$arr['pass'])."\r\n";
	}
	
	$query .= $headers;
	
	// POST ���ϡ�urlencode �����ǡ����Ȥ���
	if (strtoupper($method) == 'POST')
	{
		$POST = array();
		foreach ($post as $name=>$val)
		{
			$POST[] = $name.'='.urlencode($val);
		}
		$data = join('&',$POST);
		$query .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$query .= 'Content-Length: '.strlen($data)."\r\n";
		$query .= "\r\n";
		$query .= $data;
	}
	else
	{
		$query .= "\r\n";
	}
	
	$fp = fsockopen(
		$via_proxy ? $proxy_host : $arr['host'],
		$via_proxy ? $proxy_port : $arr['port'],
		$errno,$errstr,30);
	if (!$fp)
	{
		return array(
			'query'  => $query, // Query String
			'rc'     => $errno, // ���顼�ֹ�
			'header' => '',     // Header
			'data'   => $errstr // ���顼��å�����
		);
	}
	
	fputs($fp, $query);
	
	$response = '';
	while (!feof($fp))
	{
		$response .= fread($fp,4096);
	}
	fclose($fp);
	
	$resp = explode("\r\n\r\n",$response,2);
	$rccd = explode(' ',$resp[0],3); // array('HTTP/1.1','200','OK\r\n...')
	return array(
		'query'  => $query,             // Query String
		'rc'     => (integer)$rccd[1], // Response Code
		'header' => $resp[0],           // Header
		'data'   => $resp[1]            // Data
	);
}
// �ץ������ͳ����ɬ�פ����뤫�ɤ���Ƚ��
function via_proxy($host)
{
	global $use_proxy,$no_proxy;
	static $ip_pattern = '/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?:\/(.+))?$/';
	
	if (!$use_proxy)
	{
		return FALSE;
	}
	$ip = gethostbyname($host);
	$l_ip = ip2long($ip);
	$valid = (is_long($l_ip) and long2ip($l_ip) == $ip); // valid ip address
	
	foreach ($no_proxy as $network)
	{
		if ($valid and preg_match($ip_pattern,$network,$matches))
		{
			$l_net = ip2long($matches[1]);
			$mask = array_key_exists(2,$matches) ? $matches[2] : 32;
			$mask = is_numeric($mask) ?
				pow(2,32) - pow(2,32 - $mask) : // "10.0.0.0/8"
				ip2long($mask);                 // "10.0.0.0/255.0.0.0"
			if (($l_ip & $mask) == $l_net)
			{
				return FALSE;
			}
		}
		else
		{
			if (preg_match('/'.preg_quote($network,'/').'/',$host))
			{
				return FALSE;
			}
		}
	}
	return TRUE;
}
?>
