George Oosthuizen 38041898
Theo Ferreira 37262416
Jonathan Vorster 38204533

Everything works, go https://baastheowebsite.000webhostapp.com/ to view hosted website.

Hosting service is not the best so you might get fished or something in that line and if it does not work
then try another browser or your phone or something.

To get site up and running locally:

1. Copy repo (https://github.com/theoferr/REIIPrac2024) or download all files from repo into a folder in your xampp/htdocs folder.
2. Start MySQL and Apache
3. In Xampp create a new database and copy the downloaded SQL database into it (cmd commands):
3.1 cd C:\xampp\mysql\bin
3.2 mysql.exe -uroot
3.3 CREATE DATABASE practical;
3.4 exit
3.5 mysql.exe - uroot practical < dir_from_downloaded_database
4. Visit http://localhost/(your directory here to htdocs/folder)/index.php
5. Have fun