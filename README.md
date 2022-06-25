# Google Sheet PHP

## Local Setup
* copy .env.example to .env file, make your environment changes
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
## Variant №1:
### Create a Google Service Account using the official documentation or another accessible explainable site
- https://cloud.google.com/iam/docs/creating-managing-service-accounts
- https://robocorp.com/docs/development-guide/google-sheets/interacting-with-google-sheets#create-a-google-service-account
* Your "google sheet" share the email in your "credentials.json" file 
* Put the Google Sheet ID in "SPREAD_SHEET_ID"
* Your "google sheet" share the email in your "credentials.json" file

## Variant №2:
### If you want, you can use the already prepared test "google sheet" account
* The credentials.zip file is located at the main address of the project. Unzip it in the same place where it is located. 
* The password to open the file is as follows: ```123456789```
* visit the following link։ https://docs.google.com/spreadsheets/d/1XJ9ynjwrp4hX3iBtkCEHOmq8XTmRKDVmcfJ4c8rq4Ck/edit#gid=0
* and Login with this account 
  * email:```testwryan@gmail.com```
  * pass: ```test.wr.12345678```

## Run command ```crontab -e``` open with text editor and add following line at the end of the file

````* * * * * cd /var/www/test-php/ && php config.php````
