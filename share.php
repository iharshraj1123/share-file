<?php header("Access-Control-Allow-Origin: *");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Files</title>
    <style>
        *{
            box-sizing: border-box;
        }
        .main{
            padding: 15px 0 0 40px;
        }
        .clearfile{
            cursor: pointer;
        }
        .clearfile:hover{
            color: #7d5f5f;
        }
        .hidemepls{
            display: none;
        }
        .table-row{
            border: solid 2px black;
            border-top: 0px;
            display: flex;
        }
        .table-row:first-child{
            border: solid 2px black;
        }
        .table-cell{
            padding: 4px 10px;
            border-left: 2px solid black;
            display: inline-block;
            word-wrap: break-word;
        }
        .table-cell:first-child{
            border-left: 0;
            min-width:40px;
        }
        .table-cell:nth-child(2){
            width: 35%;
        }
        .table-cell:nth-child(3){
            width: 50%;
        }
        #download-view{
            margin-top: 15px;
        }
        @media(max-width: 850px) {
            .table-cell:nth-child(3){
            display:none;
            }
        }
    </style>
    <script>var maintain = false;</script>
</head>
<body>
    <div class="main">
    <div><?php 
    $exec = 'ipconfig | findstr /R /C:"IPv4.*"';
    exec($exec, $output);
    $matches=[];
    foreach ($output as $outputo){
        preg_match('/\d+\.\d+\.\d+\.\d+/', $outputo, $matcheso);
        foreach ($matcheso as $matchesoo){
            array_push($matches,$matchesoo);
        }
    }
    //$localIP = getHostByName(getHostName());

    $localIP = $matches;
    echo 'Host local IP Address - <span id="ip-address">'. json_encode($localIP)  . '</span>';
    ?></div>
    
    
    <div class="main2">
        <h1>Upload:</h1>
        <label for="cars">Upload Type:</label>
        <select name="cars" id="uploadType" onchange="select_change()">
            <option value="file">Files</option>
            <option value="folder">Folder</option>
        </select>
        <br><br>
        <div class="buttons">
            <button onclick="putintext('\\d:\\Video songs\\')">Video Songs</button>
            <button onclick="putintext('\\c:\\Users\\ihars\\Downloads\\')">Downloads</button>
            <button onclick="putintext('\\c:\\Users\\ihars\\Desktop\\')">Desktop</button>
            <button onclick="putintext('\\d:\\My Animes\\')">My Animes</button>
            <button onclick="putintext('\\d:\\0-entertainment\\')">0-Entertainment</button>
            <button onclick="putintext('\\d:\\0-storage\\')">0-Storage</button>
        </div><br>
        <input onchange="inputchanged()" style="width:90%;max-width:500px;" id="location" type="text"><br>
        <br>
        <input accept="video/mp4 video/webm" id="select-input" name="files[]" type="file" multiple><br>
        <span onclick="clear_inputs()" class="clearfile">Clear selected file</span><br><br>

        <button onclick="upload()">Upload</button>
        <div id="upload-status-div"></div>
        <h1>Get uploads:</h1>
        <button onclick="download_files()" class="download-btn hidemepls">Download</button>
        <br>
        <div id="download-view" class="hidemepls">
        </div>
        <br>
        <button onclick="clear_db()">Delete All uploads</button>
    </div>
    
    </div>
    <script>
        let is_localhost = window.location.href.includes("http://localhost")
        let input= document.getElementById("location")
        let temp_num = 0;
        let upload_type = document.getElementById("uploadType").value;
        let input_btn = document.getElementById("select-input")

        function absolute_pc_path(x){
            x = x.replace("\\c:\\","C:\\")
            x = x.replace("\\d:\\","D:\\")
            return x;
        }

        if(upload_type === "file") file_type()
        else folder_type()

        function select_change(){
            upload_type = document.getElementById("uploadType").value;
            if(upload_type === "file") file_type()
            else folder_type()
        }
        function file_type(){
            upload_type = "file"
            input_btn.webkitdirectory = false
        }

        function folder_type(){
            upload_type = "folder"
            input_btn.webkitdirectory = true
        }

        function putintext(x){
            input.value = x;
        }

        function inputchanged(){
            input.value = input.value.replace(/^D:[\\|\/]/gm, "\\d:\\")
            input.value = input.value.replace(/^C:[\\|\/]/gm, "\\c:\\")
            if(!input.value.endsWith("\\") && !input.value.endsWith("/") && input.value != ""){
                input.value = input.value + "\\"
            }
        }

        function clear_inputs(){
            document.getElementById('select-input').value = '';
            document.getElementById('location').value = '';
        }

        function clear_db(x){
            let clearance = false;
            if(x == "forced") clearance = true;
            else if (confirm("OK = Clear the database") == true) clearance = true;
            
            if (clearance) {
                let xmlhttp=new XMLHttpRequest();
                xmlhttp.open("POST","backend.php",true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("work=c");

                let xmlhttp2=new XMLHttpRequest();
                xmlhttp2.open("POST","clearUploads.php",true);
                xmlhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp2.send("delete");

                document.getElementById("download-view").innerHTML ="";
                document.getElementsByClassName("download-btn")[0].classList.add("hidemepls")
                document.getElementById("download-view").classList.add("hidemepls")
            }
        }

        function download_files(){
                if(window.mobileCheck()){
                    document.getElementsByClassName("click-dnd")[temp_num].click();
                    if(temp_num < document.getElementsByClassName("click-dnd").length-1) temp_num++;

                    let temp_interval = setInterval(() => {
                    let downloadcells = document.getElementsByClassName("click-dnd")
                    downloadcells[temp_num].click();
                    if(temp_num < downloadcells.length-1) temp_num++
                    else{ clearInterval(temp_interval);
                        // clear_db("forced");
                         temp_num=0}
                    }
                    , 2500); 
                }
                else{
                    let downloadcells= document.getElementsByClassName("click-dnd");
                    for(let i=0;i<downloadcells.length;i++){
                        downloadcells[i].click();
                        //if(i == downloadcells.length - 1) clear_db("forced")
                    }
                }           
            console.log("downloaded")
        }

        async function upload(){
            if(document.getElementById('location').value != '' && document.getElementById('select-input').value != '' && is_localhost){
                if(upload_type === "file") normal_upload()
                else normal_upload2()
            }
            else if (document.getElementById('select-input').value != '' && !is_localhost){
                    document.getElementById("upload-status-div").innerText = "uploading..."
                    
                    let filesos = document.getElementById("select-input").files;
                    const endpoint = "./upload.php";
                    const formDataos = new FormData();
                    formDataos.append("file_no", filesos.length);
                    let folder_name_temp;
                    if(upload_type == "folder"){
                        folder_name_temp = filesos[0].webkitRelativePath.split("/")[0] + ` [${Date.now()}]` + ".zip";
                        formDataos.append("folder_name", folder_name_temp);
                        for(let i = 0; i < filesos.length; i++){
                            formDataos.append(`inpFile${i}`, filesos[i]);
                            formDataos.append(`relPath${i}`, filesos[i].webkitRelativePath);
                        }
                    }
                    else{
                        for(let i = 0; i < filesos.length; i++){
                            formDataos.append(`inpFile${i}`, filesos[i]);
                        }
                    }
                    formDataos.append("upload_type", upload_type);

                    fetch(endpoint,{
                        method:"post",
                        body: formDataos
                    }).then(
                        function(){
                            document.getElementById('location').value = './uploads/';
                            if(upload_type == "file") normal_upload()
                            else{
                                let new_loc = `./uploads/${folder_name_temp}`
                                folder_name_temp = folder_name_temp.split("[")
                                let name_x = folder_name_temp.pop().split("]").pop()
                                console.log(name_x)
                                let xmlhttp2=new XMLHttpRequest();
                                xmlhttp2.open("POST","backend.php",true);
                                xmlhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xmlhttp2.send(`work=a2&location=${new_loc}&folder_name=${folder_name_temp.join("[")+name_x}&type=${upload_type}`);
                                xmlhttp2.onreadystatechange = function () {
                                    if (this.readyState == 4 && this.status == 200) {
                                        document.getElementById("upload-status-div").innerText = ""
                                        clear_inputs();
                                        show_uploads();
                                    }
                                }
                            }
                        }
                    ).catch(console.error)
                // }
                // else{

                // }
            }
            else if (document.getElementById('location').value != '' && document.getElementById('select-input').value == '' && upload_type == "folder" && is_localhost){
                normal_upload2()
            }
            else alert("please select files and locations")
        }

        function normal_upload(){
            let file_no = document.getElementById("select-input").files.length;
            console.log(`Beginning to upload... \n file_no = ${file_no}`)
            let file_name_arr = new Array();
            let file = document.getElementById('select-input');
            for(let i = 0; i < file.files.length; i++){
                if(file.files[i].name){
                    let temp_word = purify(file.files[i].name);
                    temp_word = encodeURI(temp_word)
                    file_name_arr.push(temp_word);
                }
                else{
                    alert("only 1 file")
                    let temp_word = purify(file.files.item(0).name);
                    file_name_arr.push(temp_word);
                }
            }
            let json_arr = JSON.stringify(file_name_arr);
            let new_loc = purify(input.value);
            console.log(`new_loc=${new_loc}`);
            console.log(`files=${json_arr}`);
            let xmlhttp=new XMLHttpRequest();
            xmlhttp.open("POST","backend.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(`work=a&location=${new_loc}&files=${json_arr}&type=${upload_type}`);
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("upload-status-div").innerText = ""
                    clear_inputs();
                    show_uploads();
                }
            }
        }
        function normal_upload2(){
            console.log(`Beginning to upload folder...`);
            let new_loc = purify(input.value);
            console.log(`new_loc=${new_loc}`);
            document.getElementById("upload-status-div").innerText = "uploading..."
            new_loc = absolute_pc_path(new_loc);
            
            if(is_localhost){
                let folder_name = new_loc.split(/[\\/]/)[new_loc.split(/[\\/]/).length - 2] + ` [${Date.now()}]` + ".zip";
                let xmlhttp=new XMLHttpRequest();
                xmlhttp.open("POST","backend.php",true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(`work=d&location=${new_loc}&folder_name=${folder_name}`);
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(xmlhttp.responseText)
                        document.getElementById("upload-status-div").innerText = "uploaded..."

                        new_loc = `./uploads/${folder_name}`
                        let xmlhttp2=new XMLHttpRequest();
                        xmlhttp2.open("POST","backend.php",true);
                        xmlhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp2.send(`work=a2&location=${new_loc}&folder_name=${folder_name}&type=${upload_type}`);
                        xmlhttp2.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("upload-status-div").innerText = ""
                                clear_inputs();
                                show_uploads();
                            }
                        }
                    }
                }
            }

        }
        function purify(x){
            x = x.replace("&","!1and1!");
            return x;
        }

        function show_uploads(){
            console.log("upload was shown")
            let xmlhttp=new XMLHttpRequest();
            xmlhttp.open("POST","backend.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("work=b");
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if(xmlhttp.responseText != "<div class='table-row'><div class='table-cell'>Id</div><div class='table-cell'>Name</div><div class='table-cell'>Location</div><div class='table-cell'>Download link</div></div>"){
                        document.getElementById("download-view").classList.remove("hidemepls")
                        document.getElementsByClassName("download-btn")[0].classList.remove("hidemepls")
                        document.getElementById("download-view").innerHTML = xmlhttp.responseText;
                    }
                }
                else{
                    console.log("no uploads found")
                    document.getElementById("download-view").innerHTML ="";
                    document.getElementsByClassName("download-btn")[0].classList.add("hidemepls")
                    document.getElementById("download-view").classList.add("hidemepls")
                }
            }
        }

        //7 Cookies
        function setCookie(cname, cvalue, exdays) {
            let d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/" + "; secure;";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        window.onload = function(){
            let new_ip = document.getElementById("ip-address").textContent
            let old_ip = getCookie("old-ip");
            if(is_localhost){
                if(!maintain) force_heroku();
            }
            else{
                document.getElementsByClassName("buttons")[0].classList.add("hidemepls")
                document.getElementById("location").classList.add("hidemepls")
            }
            //else console.log(`ip was not changed: ${new_ip}`);
            show_uploads();
            if(!maintain) setInterval(show_uploads,2000)
        }

        function force_heroku(){
            let new_ip = document.getElementById("ip-address").textContent
            let old_ip = getCookie("old-ip");
            let xmlhttp=new XMLHttpRequest();
            xmlhttp.open("POST","https://harsh-pc.herokuapp.com/index.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(`new_ip=${new_ip}`);
            setCookie("old-ip",new_ip,0.1)
            console.log(`new ip was logged on heroku: ${new_ip}`)
        }

        
        window.mobileCheck = function() {
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
        };
    </script>
</body>
</html>

