Everything works, go https://baastheowebsite.000webhostapp.com/ to view hosted website.

To get site up and running locally:

1. Copy repo (https://github.com/theoferr/REIIPrac2024) or download all files from repo into a folder in your xampp/htdocs folder.
2. In Xampp create a new database and copy the downloaded SQL database into it (cmd commands):
2.1 cd C:\xampp\mysql\bin
2.2 mysql.exe -uroot
2.3 CREATE DATABASE practical;
2.4 exit
2.5 mysql.exe - uroot practical < dir_from_downloaded_database
3. Start MySQL and Apache in
4. Visit http://localhost/(your directory here to htdocs/folder)/index.php
5. Have fun