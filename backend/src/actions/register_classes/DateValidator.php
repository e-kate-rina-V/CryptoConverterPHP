<?php
require_once __DIR__ . '/../validation_class/Validation.php';

class DateValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('date', 'Incorrect date specified');
         
            return false;
        }
        
        return true;
    }
}
