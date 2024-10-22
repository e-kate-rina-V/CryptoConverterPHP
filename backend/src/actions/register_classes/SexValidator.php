<?php
require_once __DIR__ . '/../validation_class/Validation.php';

class SexValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('sex', 'Please, select gender');
            return false;
        }
        return true;
    }
}
