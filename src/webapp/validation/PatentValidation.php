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
        if (!preg_match('/.{1,70}/', $company)) {
            $this->validationErrors[] = "Company name can't be empty and cannot exceed limit of 70 characters";
        }

        if (!preg_match('/.{1,35}/', $title)) {
            $this->validationErrors[] = "Title can't be empty and cannot exceed limit of 35 characters";
        }

        return $this->validationErrors;
    }


}
