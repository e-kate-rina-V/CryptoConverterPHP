<?php 

require_once __DIR__ . '/../validation_class/Validation.php';

class PasswordValidator extends Validation
{
    protected $user;

    public function __construct($data, $user)
    {
        parent::__construct($data);
        $this->user = $user;
    }

    public function validate(): bool
    {
        if (!password_verify($this->data, $this->user['hash_password'])) {
            setMessage('error', 'Empty or incorrect password');
            return false;
        }
        return true;
    }
}
