<?php
// include encryption class
include_once 'url_encryption_class.php';
	
// This script is used to download the selected files.
$file_name = $_REQUEST["file_url"];

if(isset($_REQUEST["file_url"])):
	
	
	if(strpos($_REQUEST["file_url"], "http://") !== false || strpos($_REQUEST["file_url"], "ftp://") !== false || strpos($_REQUEST["file_url"], "www.") !== false):
		echo 'Sorry, but you are not allowed to pass URL strings to this script!';
	else:
		// decryption class
		$encryptObject = new MyEncryption();
		echo $encryptObject->enc_decrypt($_REQUEST["file_url"]);
		Download($_SERVER['DOCUMENT_ROOT'].end(explode('http://'.$_SERVER['SERVER_NAME'],$encryptObject->enc_decrypt($_REQUEST["file_url"]))));		
	endif;

else:
	exit;
endif;

function Download($path, $speed = null)
{
    if (is_file($path) === true)
    {
        $file = @fopen($path, 'rb');
        $speed = (isset($speed) === true) ? round($speed * 1024) : 524288;

        if (is_resource($file) === true)
        {
            set_time_limit(0);
            ignore_user_abort(false);

            while (ob_get_level() > 0)
            {
                ob_end_clean();
            }

            header('Expires: 0');
            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . sprintf('%u', filesize($path)));
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Transfer-Encoding: binary');

            while (feof($file) !== true)
            {
                echo fread($file, $speed);

                while (ob_get_level() > 0)
                {
                    ob_end_flush();
                }

                flush();
                sleep(1);
            }

            fclose($file);
        }

        exit();
    }

    return false;
}

?>