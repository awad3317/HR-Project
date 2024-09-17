<?php 

class Validator {
    protected $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            if (!$this->validateRule($value, $rule)) {
                $this->errors[$field][] = "The $field field is invalid.";
            }
        }
        return empty($this->errors);
    }

    protected function validateRule($value, $rule) {
        switch ($rule) {
            case 'required':
                return !is_null($value) && $value !== '';
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            // يمكنك إضافة قواعد تحقق أخرى هنا
            default:
                return true;
        }
    }

    public function errors() {
        return $this->errors;
    }
}

?>