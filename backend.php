<?php 
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "admin";
$password = "pwdpwd";
$dbname = "sharefile";

$conn = new mysqli($servername, $username, $password, $dbname);

class FlxZipArchive extends ZipArchive 
{
public function addDir($location, $name) 
{
    $this->addEmptyDir($name);
    $this->addDirDo($location, $name);
} 
private function addDirDo($location, $name) 
{
    $name .= '/';
    $location .= '/';
    $dir = opendir ($location);
    while ($file = readdir($dir))
    {
        if ($file == '.' || $file == '..') continue;
        $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
        $this->$do($location . $file, $name . $file);
    }
} 
}

function depurify($x){
    $x =  str_replace("!1and1!","&",$x);
    return $x;
}

$work = $_POST["work"];
$type = $_POST['type'];
$files_arr = json_decode(depurify($_POST["files"]),true);
$location = depurify($_POST["location"]);

if($work == "a"){
    //upload file data
    for ($x = 0; $x < count($files_arr); $x++) {
        $loc = $location . $files_arr[$x];
        $loc = str_replace("\\","\\\\",$loc);
        $loc = str_replace("'","\\'",$loc);
        $name = urldecode($files_arr[$x]);
        $name = basename($name);
        $name = str_replace("'","\\'",$name);
        $file_data = "INSERT INTO `files` (`id`, `name`, `location`, `type`) VALUES (NULL, '$name', '$loc', '$type')";
        $result = $conn->query($file_data);
        echo "file name = $name <br><br>Location: $loc";
    } 
}
elseif($work == "a2"){
    //upload folder
    $loc = $location;
    $loc = str_replace("\\","\\\\",$loc);
    $loc = str_replace("'","\\'",$loc);
    $name = $_POST["folder_name"];
    $file_data = "INSERT INTO `files` (`id`, `name`, `location`, `type`) VALUES (NULL, '$name', '$loc', '$type')";
    $result = $conn->query($file_data);
    echo "file name = $name <br><br>Location: $loc";
}
elseif($work == "b"){
    //get file datas
    $file_data = "SELECT * FROM files";
    $result = $conn->query($file_data);
    echo "<div class='table-row'><div class='table-cell'>Id</div><div class='table-cell'>Name</div><div class='table-cell'>Location</div><div class='table-cell'>Download link</div></div>";
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $name = $row["name"];
            $loc = $row["location"];
            echo "<div class='table-row'><div class='table-cell'>$id</div><div class='table-cell name-cell'>$name</div><div class='table-cell location-cell'>$loc</div><div class='table-cell link-cell'><a href=\"$loc\" class='click-dnd' download>download</a>&nbsp;&nbsp;<a href=\"$loc\" target='_blank'>view</a></div></div>";
        }
    }
}
elseif($work == "c"){
    //delete file data;
    $file_data = "TRUNCATE files";
    $result = $conn->query($file_data);
    echo "database cleared";
}
elseif($work == "d"){
    //zip
    createZip($location);
}
elseif($work == "e"){
    echo GetDirectorySize($location);
}
  
function createZip($dir){
    //dir = new_loc, eg = D:/My Animes/
    $the_folder = $dir;
    $name = $_POST["folder_name"];
    $zip_file_name = __DIR__ . "/uploads/$name";
    $za = new FlxZipArchive;
    $res = $za->open($zip_file_name, ZipArchive::CREATE);
    if($res === TRUE) 
    {
        $za->addDir($the_folder, basename($the_folder));
        $za->close();
    }
    else{
    echo 'Could not create a zip archive';
    }
}

function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}
?>
