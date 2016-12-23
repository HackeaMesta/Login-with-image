<?php
include 'login.php';

class Key
{
    private $file;
    protected $master_key;
    protected $master_key_2;

    function __construct()
    {
        // Only for developers...
        // $master_key = sha256 = nombre del cliente
        $this->setMasterKey("a05ac87e074e841c4c3ce1631523e4c314d1806e6b32696b2a5466c47cb387e5");
    }

    function make_key($file)
    {
        $l = new LoginApi();
        $l->createMasterKey();

        $this->setFile($file);

        $orig_contents = file_get_contents($file);
        $fp = fopen($file, "wb");
        fwrite($fp, $orig_contents . "key:" . $l->returnMasterKey());
        fclose($fp);
        return "<a href='class/make.php?id=". $this->getFile() ."'>Descargar archivo</a>";
    }

    function download_file()
    {
      $mime = ($mime = getimagesize($this->getFile())) ? $mime['mime'] : $mime;
      $size = filesize($this->getFile());
      $fp = fopen($this->getFile(), "rb");
      header('Pragma: public'); 	// required
      header('Expires: 0');		// no cache
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($this->getFile())).' GMT');
      header('Cache-Control: private',false);
      header('Content-Type: '.$mime);
      header('Content-Disposition: attachment; filename="'.basename($this->getFile()).'"');
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: '.filesize($this->getFile()));	// provide file size
      header('Connection: close');
      readfile($this->getFile());
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getMasterKey()
    {
        return $this->master_key;
    }

    /**
     * @param string $master_key
     */
    public function setMasterKey($master_key)
    {
        $this->master_key = $master_key;
    }



}

if (isset($_GET['id'])) {
  $download = new Key();
  $download->setFile($_GET['id']);
  $download->download_file();
}

$uploads_dir = 'uploads/';
if (strstr($_FILES['fileKey']['type'], 'image/')) {
    $tmp_name = $_FILES["fileKey"]["tmp_name"];
    $name = $_FILES["fileKey"]["name"];
    $fullFile = "$uploads_dir/$name";
    if (!move_uploaded_file($tmp_name, $fullFile)) {
        echo "Error: " . PHP_EOL;
    } else {
        $c = new Key();
        echo $c->make_key($fullFile);
    }
} else {
    echo "Sube un archivo valido (.png .jpg .jpeg)";
}
