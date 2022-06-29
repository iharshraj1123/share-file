<?php 
$file_no = (int)$_POST["file_no"];
$upload_type = $_POST["upload_type"];

if($upload_type == "file"){
    for($i=0;$i<$file_no; $i++){
        $temp_name =  "inpFile".$i;
        $file = $_FILES[$temp_name];
        $targetpath = "uploads/" . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $targetpath);
    }
}
else{
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
    //dir = new_loc, eg = D:/My Animes/
    $name = $_POST["folder_name"];
    $za = new FlxZipArchive;
    $zip_file_name = __DIR__ . "/uploads/$name";
    $res = $za->open($zip_file_name, ZipArchive::CREATE);
    if($res === TRUE) 
    {
        for($i=0;$i<$file_no; $i++){
            $temp_name =  "inpFile".$i;
            $file = $_FILES[$temp_name];
            $temp_relpath = $_POST["relPath".$i];
            $za->addFile($file["tmp_name"], "$temp_relpath");
        }
        $za->close();
    }
    else{
    echo 'Could not create a zip archive';
    }
}
?>