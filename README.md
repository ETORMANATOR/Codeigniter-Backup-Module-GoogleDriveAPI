# Codeigniter-Backup-Module-GoogleDriveAPI

CodeIgniter Backup Module using Google Drive API(Service Accounts)

## Error if you Run this In your local machine XAMPP

Message: mkdir(): Permission denied
How to Fix: Change the backup folder Permission to 777 (chmod 777 backup)

## Dont Forget to Import autobackup.sql

## test2.json You can get this in you google console https://console.cloud.google.com/apis/credentials?project

Other Tutorial Create your own Service Account https://support.google.com/a/answer/7378726?hl=en

## backup folder where to save your backup local after saving it to local it will upload to your Google Drive.

### In Application/controllers/Welcome.php line 148 chnage the email to your Google Drive Email Address
