<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Patent;

class PatentValidation {

    private $validationErrors = [];

    public function __construct($company, $title) {
        return $this->validate($company, $title);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }


    public function validate($company, $title)
    {
        if ($company == null) {
            $this->validationErrors[] = "Company/User needed";

        }
        if ($title == null) {
            $this->validationErrors[] = "Title needed";
        }

        $target_dir = getcwd() . "/web/uploads/";
        $targetFile = $target_dir . basename($_FILES['uploaded']['name']);
        $file_name = $_FILES['uploaded']['name'];
        $file_size =$_FILES['uploaded']['size'];
        $file_tmp =$_FILES['uploaded']['tmp_name'];
        $file_type=$_FILES['uploaded']['type'];
        $explode = explode('.',$file_name);
        $file_ext=strtolower(end($explode));
        $expensions= array("jpeg","jpg","png");

        if(in_array($file_ext,$expensions)=== false){
            $this->validationErrors[] ="extension not allowed, please choose a JPEG or PNG file.";
        }

        if($file_size > 2097152){
            $this->validationErrors[]  ='File size must be under 2 MB';
        }
        return $this->validationErrors;
    }


}
