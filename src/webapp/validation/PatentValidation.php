<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Patent;

class PatentValidation {

    private $validationErrors = [];

    public function __construct($title, $company, $description) {
        $this->validate($title, $company, $description);
    }

    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function validate($title, $company, $description)
    {
        if ((strlen($title) > 35) or empty($title)) {
            $this->validationErrors[] = "Title can't be empty and cannot exceed limit of 35 characters";
        }

        if ((strlen($company) > 70) or empty($company)) {
            $this->validationErrors[] = "Company name can't be empty and cannot exceed limit of 70 characters";
        }

        if ((strlen($description) > 400) or empty($description)) {
            $this->validationErrors[] = "Description can't be empty and cannot exceed limit of 400 characters";
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
    }


}
