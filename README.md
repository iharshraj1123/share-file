# share-file

![image](https://user-images.githubusercontent.com/33609172/176454983-b5a8aa79-4021-4ce3-8f11-acb38e3f6784.png)

Simply share files between computers in a LAN, or through tunnelling, or simply hosting on some server.

You can share the whole files or whole folder, but folders can be uploaded only from a PC phone browsers have not allowed such features yet (webkitdirectory).

# Requirements:

- (optional) basic knowledge of php, mysql and apache servers.

- PHP, MySQL. 

- Just use XAMPP for PHP and MySQL, its much easier and you get phpmyadmin in the bundle as well.

- setup php.ini and httpd.conf so server can access C:/ and D:/ drives using "/c:/" and "/d:/". (More about this is in #Setting up PHP.ini and #Setting up httpd.conf)

# How to Use

- Share.php is the front end for the app.

- Copy paste the sql from sharefile.sql.

**When you are on the host PC (the pc on which server is hosted, url contains localhost).**

- In File sharing mode, Click on browse, copy paste the local address of the folder that contains the files you want to share. eg, "D:/games/". Then select all those files. Hit upload.

- Make shortcuts for your frequent places using the buttons.

- In Folder sharing mode, your folders will be converted into zip files.

**When you are on Some PC on the network**

- Simply upload the files/folders, no need to copy paste addresses.

# Setting up PHP.ini

- max_input_time=60
- memory_limit=1500M
- post_max_size=5000M
- upload_max_filesize=5000M
- max_file_uploads=20000

# Setting up httpd.conf

- under ```"<Directory "C:/xampp/htdocs">...</Directory>"```, add:
```
<Directory "D:/">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
<Directory "C:/">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```
- inside ```"<IfModule alias_module>"``` do:
  
``` 
  <IfModule alias_module>
    ....
    ScriptAlias /cgi-bin/ "C:/xampp/cgi-bin/"
    Alias "/d:" "D:/"
    Alias "/c:" "C:/"
  </IfModule>
```
