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

                if (strpos($singleRule, ':') !== false) {
                    list($singleRule, $param) = explode(':', $singleRule);
                }

                
                if ($singleRule === 'unique' && $param) {
                    $parts = explode(',', $param);
                    $table = $parts[0]; 
                    $column = $parts[1] ?? 'name'; 
                    if (!$this->validateUnique($value, $table, $column)) {
                        $this->errors[$field][] = $this->getErrorMessage($field, 'unique');
                    }
                } else {
                    if (!$this->validateRule($value, $singleRule, $param)) {
                        $this->errors[$field][] = $this->getErrorMessage($field, $singleRule);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    protected function validateRule($value, $rule, $param = null) {
        switch ($rule) {
            case 'required':
                return !is_null($value) && $value !== '';
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'max':
                return $this->validateMax($value, $param);
            case 'min':
                return $this->validateMin($value, $param);
            case 'boolean':
                return is_bool($value);
            case 'full_name':
                return $this->validateFullName($value);
            default:
                return true;
        }
    }

    private function validateMin($value, $min) {
        return is_string($value) ? strlen($value) >= (int)$min : true;
    }

    private function validateMax($value, $max) {
        return is_string($value) ? strlen($value) <= (int)$max : true;
    }

    private function validateFullName($value) {
        $parts = preg_split('/\s+/', trim($value));
        return count($parts) >= 4 && count($parts) <= 5;
    }

    private function validateUnique($value, $table, $column) {
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count == 0; 
    }

    protected function getErrorMessage($field, $rule) {
        $messages = [
            'required' => "حقل $field مطلوب.",
            'email' => "يجب أن يكون حقل $field عنوان بريد إلكتروني صالح.",
            'exists' => "الاختيار في حقل $field غير موجود.",
            'max' => "يجب ألا يتجاوز حقل $field الحد الأقصى.",
            'min' => "يجب أن يكون حقل $field على الأقل الحد الأدنى.",
            'boolean' => "يجب أن يكون حقل $field صحيحًا أو خاطئًا.",
            'full_name' => "يجب أن يكون اسم الموظف رباعي أو خماسي.",
            'unique' => "الحقل  موجود مسبقًا.",
        ];

        return $messages[$rule] ?? "The $field field is invalid for rule: $rule.";
    }

    public function errors() {
        return $this->errors;
    }
}

?>