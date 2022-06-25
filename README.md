# Google Sheet PHP

## Local Setup
* copy .env.example to .env file, make your environment changes
    * Put the Google Sheet ID in "SPREAD_SHEET_ID"
    * Put the local mysql password in "DB_PASSWORD"
    * Modify the remaining data if necessary

### write these commands in bash
````
sudo chown -R developer:www-data /var/www/test-php/
sudo chmod -R g+rwX /var/www/test-php/
cd /var/www/test-php
composer dump-autoload
composer require google/apiclient:^2.0
````

### Create a Google Service Account using the official documentation or another accessible explainable site
- https://cloud.google.com/iam/docs/creating-managing-service-accounts
- https://robocorp.com/docs/development-guide/google-sheets/interacting-with-google-sheets#create-a-google-service-account
* Your "google sheet" share the email in your "credentials.json" file

### kam ete uzum eq karox eq ogtvel arden patrastvac testvi google sheet hashvic
proyekti meji glxavor pati meji credentials.zip fil@ bacum enq nuyn texum vortex inq@ gtnvum e
erb porceq meji fail@ bacel kharcni password havaqeq 123456789
ev mteq hetevjal google hashiv@
https://docs.google.com/spreadsheets/d/1XJ9ynjwrp4hX3iBtkCEHOmq8XTmRKDVmcfJ4c8rq4Ck/edit#gid=0
email ==
password ==



* Your "google sheet" share the email in your "credentials.json" file

#### Run command ```crontab -e``` open with text editor and add following line at the end of the file

````* * * * * cd /var/www/test-php/ && php config.php````
