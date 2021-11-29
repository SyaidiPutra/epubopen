<?php
define('JAVA_LOC', '/usr/bin/java');
define('TIDY_LOC', '/usr/bin/tidy');
define('EPUBCHECK', '/usr/local/epubcheck-1.1/epubcheck-1.1.jar');
define('UPLOAD_DIR', '/tmp');
define('ZIP_LOC','/usr/bin/zip');

class EpubRead {
    public function __construct() // GLOBAL
    {
        $this->packTmp = sys_get_temp_dir().'/epubReadingTmp/';
        $this->workDir = uniqid();
        $this->ZipTool = new ZipArchive();
    }
    public function DirTool($app = NULL)
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $thisPath = dirname($_SERVER['PHP_SELF']);
        $thisPath = dirname(str_replace('\\','/',__FILE__));
        $onlyPath = str_replace($rootPath, '', $thisPath);

        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }
        $uri .= $_SERVER['HTTP_HOST'];

        return ($onlyPath == NULL) ? $uri . '/' . $onlyPath : $uri . $onlyPath . '/' . $app;
    }
    public function setupWork($file) // for make work room for epub
    {
        if($this->ZipTool->open($file)){
            $workRoom = $this->packTmp . $this->workDir;
            $this->ZipTool->extractTo($workRoom);
            return $workRoom;
        }else{
            $this->errorMsg("File can't Open");
            return false;
        }
    }
    public function read($file, $viewUser=False , $viewSrc=NULL) // for call read epub
    {
        // var_dump($_SERVER, $this->DirTool());
        // die();
        $room = $this->setupWork($file);
        // echo $room;
        if($room !== FALSE){
            $opf = $this->seeContent($room);
            $catalog = $this->catalogBook($room, $opf);
            $book = $this->openBook($room, $opf);
            if($viewUser === false){
                $this->templateEngine($book, $catalog);
            }else{

            }
        }
    }
    public function errorMsg($msg) // for display error
    {
        echo '
            <script>
                alert("Error: '.$msg.'")
            </script>
        ';
    }
    public function seeContent($file) // for get location opf
    {
        // echo $file;
        if(file_exists($file. '/META-INF/container.xml')){
            $dataContainer = simplexml_load_file($file. '/META-INF/container.xml') or die($this->errorMsg("Container failed to load"));
            $dataContainer = $this->ObjToArray($dataContainer);
            $opfLoc = $dataContainer['rootfiles']['rootfile']['@attributes']['full-path'];
            return $opfLoc;
        }else{
            $this->errorMsg("File container missing");
        }
    }
    public function ObjToArray($array) //convert Objek to Array
    {
        return json_decode(json_encode($array), true);
    }
    public function openBook($room, $opf)
    {
        if(file_exists($room. '/' . $opf)){
            $dataOPF = simplexml_load_file($room. '/' . $opf) or die($this->errorMsg("OPF file failed to load"));
            $dataOPF = $this->ObjToArray($dataOPF);
            $page = array();

            $locaTemp = $room . '/' . $opf;
            $locaTempSplit = explode('/', $locaTemp);
            $fileOPFName = end($locaTempSplit);
            $locaTemp = trim($locaTemp, $fileOPFName);

            $bookMeta['img'] =NULL;
            $bookMeta['page'] =NULL;
            $bookMeta['css'] =NULL;

            foreach($dataOPF['manifest']['item'] as $dataManifest){
                $type = $dataManifest['@attributes']['media-type'];
                $href = $dataManifest['@attributes']['href'];
                switch ($type) {
                    case 'application/xhtml+xml':
                        $bookMeta['page'][] = $locaTemp . $href;
                        break;
                    case 'text/css':
                        $bookMeta['css'][] = $locaTemp . $href;
                        break;
                    case 'image/png':
                        $bookMeta['img'][$href] = $locaTemp . $href;
                        break;
                }
            }
            $bookMeta['baseUrl'] = str_replace("\\", '/', $locaTemp);
            return $bookMeta;
        }else{
            $this->errorMsg("File not exists");
        }
    }

    public function catalogBook($room, $opf)
    {
        if(file_exists($room. '/' . $opf)){
            $dataOPF = simplexml_load_file($room. '/' . $opf) or die($this->errorMsg("OPF file failed to load"));
            $metadata = $dataOPF->metadata->children('dc', true);
            $metadata = $this->ObjToArray($metadata);
            return $metadata;
        }else{
            $this->errorMsg("File not exists");
        }
    }

    public function templateEngine($book, $catalog)
    {
        // die(var_dump($book, $catalog));
        $img = $this->EngineImg($book);
        $url = $this->DirTool('imgTarget.php');
        require('view.php');
    }
    public function base64IMG($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    public function EngineImg($book)
    {
        $img = $book['img'];
        // die(var_dump($book));
        if(!empty($img)){
            foreach($img as $target => $location){
                $imgBase64[$target] = $this->base64IMG($location);
            }
            return json_encode($imgBase64);
        }else{
            return NULL;
        }
    }
    public function imgTarget()
    {
        $path = $_POST['path'];
        $file = $_POST['file'];
        if($path && $file){
            $location = $path . $file;
            $this->base64IMG($location);
            echo $imgBase64;
        }else{
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            echo '404 Not Found';
        }

    }
    
}