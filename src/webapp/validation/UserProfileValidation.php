<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class UserProfileValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct()
    {
    }

    public function validateNewUser($username, $password, $firstName, $lastName, $phone, $company)
    {
        $this->validateUsername($username);
        $this->validatePassword($password);
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validatePhone($phone);
        $this->validateCompany($company);
    }

    public function validateEditUser($firstName, $lastName, $phone, $company, $email)
    {
        $this->validateFirstname($firstName);
        $this->validateLastName($lastName);
        $this->validatePhone($phone);
        $this->validateCompany($company);
        $this->validateEmail($email);
    }

    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function validatePassword($password) {
        if (!preg_match('/.{8,254}/', $password)) {
            $this->validationErrors[] = 'Password must at least be 8 characters and at most 254 characters long';
        }
    }

    public function validateUsername($username) {
        if (!preg_match('/.{3,254}/', $username)) {
            $this->validationErrors[] = 'Username must at least be 3 characters and at most 254 characters long';
        }

        if (preg_match('/^[A-Za-z0-9_]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
    }

    public function validateFirstName($firstName) {
        if (!preg_match('/.{1,35}/', $firstName)) {
            $this->validationErrors[] = "First name can't be empty and cannot exceed limit of 35 characters";
        }
    }

    public function validateLastName($lastName) {
        if (!preg_match('/.{1,35}/', $lastName)) {
            $this->validationErrors[] = "Last name can't be empty and cannot exceed limit of 35 characters";
        }
    }

    public function validateCompany($company) {
        if (!preg_match('/.{1,70}/', $company)) {
            $this->validationErrors[] = "Company name can't be empty and cannot exceed limit of 70 characters";
            return;
        }

        if (!preg_match('/[^0-9]/', $company)) {
            $this->validationErrors[] = 'Company can only contain letters';
        }
    }

    public function validatePhone($phone) {
        if (!preg_match('/.{8,8}/', $phone)) {
            $this->validationErrors[] = 'Phone number has to be exactly 8 digits';
        }
    }

    public function validateEmail($email) {
        if (!preg_match('/.{3,254}/', $email)) {
            $this->validationErrors[] = 'Email must at least be 3 characters and at most 254 characters long';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = "Invalid email format on email";
        }
    }
}
