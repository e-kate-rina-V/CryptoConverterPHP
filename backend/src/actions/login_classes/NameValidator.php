<?php

require_once __DIR__ . '/../validation_class/Validation.php';

class NameValidator extends Validation
{
    public function validate(): bool
    {
        $user = findUser($this->data);

        if (empty($this->data) || !is_string($this->data)) {
            setValidationError('name', 'Please, enter your name');
            return false;
        } elseif (!$user) {
            setMessage('error', "User $this->data not found");
            return false;
        }
        return true;
    }
}
