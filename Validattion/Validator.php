<?php 

class Validator {
    protected $errors = [];
    protected $db;     

    public function __construct($db) {
        $this->db = $db;     
    }

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleParts = explode('|', $rule);  

            foreach ($ruleParts as $singleRule) {
                $param = null; 
                $table = null;

                if (strpos($singleRule, ':') !== false) {
                    list($singleRule, $param) = explode(':', $singleRule);
                }

                if (!$this->validateRule($value, $singleRule, $param, $table)) {
                    $this->errors[$field][] = $this->getErrorMessage($field, $singleRule);
                }
            }
        }
        return empty($this->errors);
    }

    protected function validateRule($value, $rule, $param = null, $table = null) {
        switch ($rule) {
            case 'required':
                return !is_null($value) && $value !== '';
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'exists':
                return $this->checkIfExists($value, $table);
            case 'max':
                return $this->validateMax($value, $param);
            case 'min':
                return $this->validateMin($value, $param);
            case 'boolean':
                return is_bool($value);
            default:
                return true;
        }
    }

    private function validateMin($value, $min) {
        return $value >= $min;
    }

    private function validateMax($value, $max) {
        return is_string($value) ? strlen($value) <= (int)$max : true;
    }

    private function checkIfExists($id, $table) {
        if ($table && is_numeric($id)) {
            $query = "SELECT COUNT(*) AS count FROM $table WHERE id = $id";
            $result = $this->db->query($query);
            $count = $result->fetch_assoc()['count'] ?? 0;
            return $count > 0;
        }
        return false;
    }

    protected function getErrorMessage($field, $rule) {
        $messages = [
            'required' => "حقل $field مطلوب.",
            'email' => "يجب أن يكون حقل $field عنوان بريد إلكتروني صالح.",
            'exists' => "الاختيار في حقل $field غير موجود.",
            'max' => "يجب ألا يتجاوز حقل $field الحد الأقصى.",
            'min' => "يجب أن يكون حقل $field على الأقل الحد الأدنى.",
            'boolean' => "يجب أن يكون حقل $field صحيحًا أو خاطئًا.",
        ];

        return $messages[$rule] ?? "The $field field is invalid for rule: $rule.";
    }

    public function errors() {
        return $this->errors;
    }
}

?>