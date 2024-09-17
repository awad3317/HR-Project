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
            
                if (strpos($singleRule, ':') !== false) {
                    list($singleRule, $table) = explode(':', $singleRule);
                } else {
                    $table = null;
                }

                if (!$this->validateRule($value, $singleRule, $table)) {
                    $this->errors[$field][] = "The $field field is invalid for rule: $singleRule.";
                }
            }
        }
        return empty($this->errors);
    }

    protected function validateRule($value, $rule, $table = null) {
        switch ($rule) {
            case 'required':
                return !is_null($value) && $value !== '';
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'exists':
                return $this->checkIfExists($value, $table);
            case 'max':
                return $this->validateMax($value, $param);
            default:
                return true;
        }
    }

    private function validateMax($value, $max) {
        return is_string($value) ? strlen($value) <= (int)$max : true;
    }

    private function checkIfExists($id, $table) {
        if ($table && is_numeric($id)) {
            $query = "SELECT COUNT(*) AS count FROM $table WHERE id = $id";
            $resuts = $this->db->query($query);
            foreach($resuts as $result){$count= $result['count'];}
            return $count > 0;
        }
        return false;
    }

    public function errors() {
        return $this->errors;
    }

}

?>