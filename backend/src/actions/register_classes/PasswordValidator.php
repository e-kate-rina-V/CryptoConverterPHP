<?php 
require_once __DIR__ . '/../validation_class/Validation.php';

class PasswordValidator extends Validation
{
    protected $passwordConfirmation;

    public function __construct($data, $passwordConfirmation)
    {
        parent::__construct($data);
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function validate(): bool
    {
        $password = $this->data;

        if (empty($this->data)) {
            setValidationError('password', 'Password is empty');
            
            return false;
        } elseif (strlen($password) < 8) {
            setValidationError('password', 'The password must contain at least 8 characters');
           
            return false;
        } elseif (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            setValidationError('password', 'The password must contain both letters and numbers');
           
            return false;
        } elseif ($this->data !== $this->passwordConfirmation) {
            setValidationError('password', 'Passwords do not match');
            
            return false;
        }
       
        return true;
    }
}
