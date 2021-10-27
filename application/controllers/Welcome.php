<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('url','directory','file','path'));
        $this->load->model('Backup_model');
        $this->load->library('zip'); 

        $this->load->dbutil();
		// Deprecated but still works
		$config['global_xss_filtering'] = TRUE;
		//css csrf protection
		$config['csrf_protection'] = TRUE;
	}
public function start(){


    $dateandtimeBackup = date('m-d-Y').'-'.date("h:i:sa");//set date and time
    mkdir('./backup/'. $dateandtimeBackup,0755,TRUE);//create backup Directory
    //create db backup
    $prefs = array(     
        'format'      => 'zip',             
        'filename'    => 'db_backup.sql'
        );
    $backup = $this->dbutil->backup($prefs);
    write_file('./backup/'.$dateandtimeBackup.'/db.zip',$backup);//backup the Database
    $this->zip->read_dir('../public_html',true);
           
    if($this->zip->archive('./backup/'.$dateandtimeBackup.'/AdminPortal.zip')){
        $pathFileBackupLocal = set_realpath('./backup/'.$dateandtimeBackup);
        if($this->apitest($dateandtimeBackup)=="file uploaded successfully"){
            echo "done";
        }else{
            echo "error";
        }
    }
    else{
        echo "error";
    }

    
}
public function setCron(){
    $Sethours = $this->input->post("hours");
    $Setminutes = $this->input->post("minutes");
    $backuptime = $Sethours.":".$Setminutes;
    $backupPath = $this->input->post("path");

    // Start Filter backup path remove / at the first and end

    $stringFirst = substr($backupPath,0,1);
    $stringLast = substr($backupPath,-1);
    if($stringFirst == "/"){
        //remove 1st
        $remove1 = substr($backupPath,1);
        if($stringLast == "/"){
            //remove last
            $remove2 = substr($remove1,0,-1);
            $finalpath = $remove2;
        }else{
            $finalpath = $remove1;
        }

    }else{
        if($stringLast == "/"){
            //remove last
            $remove2 = substr($backupPath,0,-1);
            $finalpath = $remove2;
        }else{
            $finalpath = $backupPath;
        }
    }

// End Filter backup path remove / at the first and end
    $data = [
        "admin_enable_backup" =>"1",
        "backup_type" => $this->input->post("type"),

        "backup_time" => $backuptime,
        "backup_path" => $finalpath,

    ];
   

    if($this->input->post("type") == "Daily"){
        shell_exec('crontab -r');//delete 1st the current cron job
        // $output = shell_exec('crontab -l');
        file_put_contents('/tmp/crontab.txt', $Setminutes." ".$Sethours." * * * curl http://localhost/Codeigniter-Backup-Module-GoogleDriveAPI/Welcome/start".PHP_EOL);
        //update the cron job
        exec('crontab /tmp/crontab.txt');
        if($this->Backup_model->updatecheck($data)){
        echo  "done";
        }else{
            echo  "error";
        }
    }else if($this->input->post("type") == "Minutes"){
        shell_exec('crontab -r');//delete 1st the current cron job
        // $output = shell_exec('crontab -l');
        file_put_contents('/tmp/crontab.txt', "*/".$Setminutes." * * * * curl http://localhost/Codeigniter-Backup-Module-GoogleDriveAPI/Welcome/start".PHP_EOL);
        //update the cron job
        exec('crontab /tmp/crontab.txt');
        if($this->Backup_model->updatecheck($data)){
            echo  "done";
        }else{
            echo  "error";
        }
        
    }
    
   
   
}
public function checkifenable(){

    $data = $this->Backup_model->check();
    echo (json_encode($data));
}



public function disableautobackup(){
    shell_exec('crontab -r');//delete 1st the current cron job
    $data = [
        "admin_enable_backup" =>"0",
        "backup_type" => null
    ];
    if($this->Backup_model->updatecheck($data)){
        echo  "done";
    }else{
        echo  "error";
    }
    
}


private function apitest($dateandtimeBackup){
         // start update v2.1 update/insert the google sheets data 
		require __DIR__ . '/../../vendor/autoload.php';
        $client = new Google\Client();
        $client->setApplicationName("test");
        $client->setAuthConfig(__DIR__ . '/../../test2.json');
        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setSubject('example@gmail.com');//email of your Google drive
        $client->setAccessType('offline');
        $service = new Google_Service_Drive($client);
        $file = new Google_Service_Drive_DriveFile();//For Directory
        $file2 = new Google_Service_Drive_DriveFile();//For Files
        // $filename ="AdminPortal.zip";
       
        $Backupdetails = $this->backupDetails();
        $pathorig = $Backupdetails[0]->backup_path."/".$dateandtimeBackup;
        $pathslice = explode("/",$pathorig);
        $pathlength = count($pathslice);

        
        $getAllBackupFile = array("AdminPortal.zip","db.zip");

        $arrfolderId = [];
        $existarrfolderId = [];
        for($x = 0; $x < $pathlength; $x++){
            $res = $service->files->listFiles(array("q" => "name='{$pathslice[$x]}' and trashed=false"));
            if (count($res->getFiles()) == 0) {
                // 2. When the folder name is NOT existing, the folder is created by the folder name and the folder ID of the created folder is returned.
                
                if($x == 0){

                $file->setName($pathslice[$x]);
                $file->setMimeType('application/vnd.google-apps.folder');
                $createdFolder = $service->files->create($file);
                $folderId = $createdFolder->getId();
                array_push($arrfolderId,$folderId);
                // echo "Root Create Folder successfully";
                
                if($x == $pathlength-1){//if No sub Folder or Only store in root folder then save the back up file
                    //upload
                    
                    $file2->setDescription('Backup File');
                    $file2->setParents(array($folderId));
                    array_push($arrfolderId,$folderId);
                    for($arrfile = 0; $arrfile < count($getAllBackupFile); $arrfile++){
                        
                        $file2->setName($getAllBackupFile[$arrfile]);
                        $data = file_get_contents(__DIR__ . "/../../backup/".$dateandtimeBackup."/".$getAllBackupFile[$arrfile]);
                        $createdFile = $service->files->create($file2, array(
                        'data' => $data,
                        'uploadType' => 'multipart'
                    ));
                    if($x == $pathlength-1 && $arrfile == count($getAllBackupFile)-1){
                        if( $createdFile ){
                            
                            return "file uploaded successfully";
                        } else { 
                            return "Something went wrong.";
                        }
                    }
                    }
                    
                }
                }else{//if there is sub folder then save the backup file to the last sub folder
                    $file->setName($pathslice[$x]);
                    $file->setMimeType('application/vnd.google-apps.folder');
                    $file->setParents(array($arrfolderId[$x-1]));
                    $createdFolder = $service->files->create($file);
                    $folderId = $createdFolder->getId();
                    array_push($arrfolderId,$folderId);
                    // echo "Sub Create Folder successfully";


                    if($x == $pathlength-1){//if No sub Folder or Only store in root folder then save the back up file
                        //upload
                        
                        $file2->setDescription('Backup File');
                        $file2->setParents(array($folderId));
                        array_push($arrfolderId,$folderId);
                        for($arrfile = 0; $arrfile < count($getAllBackupFile); $arrfile++){
                            
                            $file2->setName($getAllBackupFile[$arrfile]);
                            $data = file_get_contents(__DIR__ . "/../../backup/".$dateandtimeBackup."/".$getAllBackupFile[$arrfile]);
                            $createdFile = $service->files->create($file2, array(
                            'data' => $data,
                            'uploadType' => 'multipart'
                        ));
                        if($x == $pathlength-1 && $arrfile == count($getAllBackupFile)-1){
                            if( $createdFile ){
                                
                                return "file uploaded successfully";
                            } else { 
                                return "Something went wrong.";
                            }
                        }
                        }
                        
                    }
                }
            } else {// 3. When the folder of the folder name is existing, the folder ID is returned.
                
                $folderId = $res->getFiles()[0]->getId();
                array_push($arrfolderId,$folderId);
                // echo "exist";
                // 4. The file is uploaded to the folder using the folder ID.
                if($x == $pathlength-1){
                $file2->setDescription('Backup File');
                $file2->setParents(array($arrfolderId[$pathlength-1]));
                for($arrfile = 0; $arrfile < count($getAllBackupFile); $arrfile++){
                    $file2->setName($getAllBackupFile[$arrfile]);
                $data = file_get_contents(__DIR__ . "/../../backup/".$dateandtimeBackup."/".$getAllBackupFile[$arrfile]);
                $createdFile = $service->files->create($file2, array(
                    'data' => $data,
                    'uploadType' => 'multipart'
                ));
                if($x == $pathlength-1 && $arrfile == count($getAllBackupFile)-1){
                    if( $createdFile ){
                        
                        return "file uploaded successfully";
                    } else { 
                        return "Something went wrong.";
                    }
                }
                }
                }
                }
        }

       
    
    }
    private function backupDetails(){

        $data = $this->Backup_model->check();
        return $data;
    }
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
