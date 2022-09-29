<?php
set_time_limit (0);
define ("DEFAULT_STORAGE", "/zed/files/");
define ("DEFAULT_BUF_SIZE", 8192);
// * Display directly
// * @const	CD_DISPLAY
define ("CD_DISPLAY", "inline");
// * Save to disk
// * @const	CD_SAVE
define ("CD_SAVE", "attachment");
// * @const	CT_APP_OS
define ("CT_APP_OS", "application/octet-stream");
// * @const	HDR_X_SCRIPT
define ("HDR_X_SCRIPT", "X-Script: NDL");
// * @const	CON_STATUS_NORMAL
// * @access	private
define ("CON_STATUS_NORMAL", 0);
class NDL
{
	var $vars;
	var $server;
	var $fileName;
	var $fileTime;
	var $storedFileName;
	var $contentSize;
	var $storageDir;
	var $storedFileSize;
	var $httpContentDisposition;
	var $httpContentDescription;
	var $httpContentType;
	var $bufSize;
	/**
	 * @param 	$type	integer
	 * @param 	$content	string
	 * @access	public
	 * @final
	 */
	function NDL ($file, $filename, $storage=DEFAULT_STORAGE, $description=false, $type=CD_SAVE, $content=CT_APP_OS)
	{
		$this->storageDir = $storage;
		$this->bufSize = DEFAULT_BUF_SIZE;
		$this->fileName = $file;
		$this->storedFileName = $filename;
		$this->httpContentType = $content;
		$this->httpContentDisposition = $type;
		$this->httpContentDescription = $description;

		if (isset($HTTP_GET_VARS))
		{ $this->vars = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_POST_FILES); }
		else
		{ $this->vars = &$_REQUEST; }

		if (isset($_SERVER))
		{ $this->server = &$_SERVER; }
		else
		{ $this->server = &$GLOBALS["HTTP_SERVER_VARS"]; }
	} // end function NDL
	function _NDL()
	{

	} // end function _NDL
	function send ()
	{
		if ( !$this->isAllowed() )
		{
			$this->http403 ();
			$this->updateStat ("403");
		}
		elseif ( (!isset($this->storedFileName)) || empty($this->storedFileName) || (! file_exists( $this->storageDir . $this->storedFileName)) )
		{
			$this->http404 ();
			$this->updateStat ("404");
		}
		else
		{
			$this->fileTime = filemtime ($this->storageDir . $this->storedFileName);
			$this->storedFileSize = filesize ( $this->storageDir . $this->storedFileName);
			$fd = fopen ($this->storageDir.$this->storedFileName, "rb");
			if ( isset($this->server["HTTP_RANGE"]) )
			{
				preg_match ("/bytes=(\d+)-/", $this->server["HTTP_RANGE"], $m);
				$offset = intval($m[1]);
				$this->contentSize = $this->storedFileSize - $offset;
				fseek ($fd, $offset);
				$this->updateStat ("206");
				$this->http206 ();
			}
			else
			{
				$this->contentSize = $this->storedFileSize;
				$this->updateStat ("200");
				$this->http200 ();
			}
			while ( !feof($fd) && (connection_status() == CON_STATUS_NORMAL) )
			{
				$contents = fread ($fd, $this->bufSize);
				echo $contents;
			}
			fclose ($fd);
		}
	} // end function send
	function http200 ()
	{
		header ("HTTP/1.1 200 OK");
		header ("Date: " . $this->getGMTDateTime ());
		header ("X-Powered-By: PHP/" . phpversion());
		header (HDR_X_SCRIPT);
		header ("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
		header ("Last-Modified: " . $this->getGMTDateTime ($this->fileTime) );
		header ("Cache-Control: None");
		header ("Pragma: no-cache");
		header ("Accept-Ranges: bytes");
		header ("Content-Disposition: " . $this->httpContentDisposition . "; filename=\"" . $this->fileName . "\"");
		header ("Content-Type: " . $this->httpContentType);
		if ($this->httpContentDescription)
			header ("Content-Description: " . $this->httpContentDescription );
		header ("Content-Length: " . $this->contentSize);
		header ("Proxy-Connection: close");
		header ("");
	} // end function http200
	function http206 ()
	{
		$p1 = $this->storedFileSize - $this->contentSize;
		$p2 = $this->storedFileSize - 1;
		$p3 = $this->storedFileSize;

		header ("HTTP/1.1 206 Partial Content");
		header ("Date: " . $this->getGMTDateTime ());
		header ("X-Powered-By: PHP/" . phpversion());
		header (HDR_X_SCRIPT);
		header ("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
		header ("Last-Modified: " . $this->getGMTDateTime ($this->fileTime) );
		header ("Cache-Control: None");
		header ("Pragma: no-cache");
		header ("Accept-Ranges: bytes");
		header ("Content-Disposition: " . $this->httpContentDisposition . "; filename=\"" . $this->fileName . "\"");
		header ("Content-Type: " . $this->httpContentType);
		if ($this->httpContentDescription)
			header ("Content-Description: " . $this->httpContentDescription );
		header ("Content-Range: bytes " . $p1 . "-" . $p2 . "/" . $p3);
		header ("Content-Length: " . $this->contentSize);
		header ("Proxy-Connection: close");
		header ("");
	} // end function http206
	function http404 ()
	{
		header ("HTTP/1.1 404 Object Not Found");
		header ("X-Powered-By: PHP/" . phpversion());
		header (HDR_X_SCRIPT);
	} // end function http404
	function http403 ()
	{
		header ("HTTP/1.1 403 Forbidden");
		header ("X-Powered-By: PHP/" . phpversion());
		header (HDR_X_SCRIPT);
		header ("");
	} // end function http403
	function getGMTDateTime ($time=NULL)
	{
		$offset = date("O");
		$roffset = "";
		if ($offset[0] == "+")
		{
			$roffset = "-";
		}
		else
		{
			$roffset = "+";
		}
		$roffset .= $offset[1].$offset[2];
		if (!$time)
		{
			$time = Time();
		}
		return (date ("D, d M Y H:i:s", $time+$roffset*3600 ) . " GMT");
	} // end function getGMTDateTime
	function isAllowed ()
	{
		return true;
	} // end function isAllowed
	function updateStat ($code)
	{
		return true;
	} // end function updateStat

} // end class NDL
?>