<?php
require_once __DIR__ . '/../validation_class/Validation.php';

class AvatarValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('avatar', 'Please, select an avatar');

            return false;
        } elseif (!empty($this->data)) {
            $types = ['image/jpeg', 'image/png'];
            if (!in_array($this->data['type'], $types)) {
                setValidationError('avatar', 'Incorrect type of avatar image');
                
                return false;
            }
            if (($this->data['size'] / 1000000) >= 1) {
                setValidationError('avatar', 'The image must be no larger than 1 MB.');
               
                return false;
            }
        }
      
        return true;
    }
}
