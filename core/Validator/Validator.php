<?php
namespace Core\Validator;

use Core\Services\Cookie;

class Validator {
    protected $data = [];
    protected $rules = [
        'email' => 'required|email',
        'terms' => 'required',
        'password' => 'required|min:5'
    ];
    protected $errors = 0;
    protected $cookie;

    public function __construct() {
        // $this->data = $data;
        // $this->rules = $rules;
        $this->cookie = Cookie::getInstance();
    }

    /**
     * Валідація даних
     * @param array $data дані форми
     * @param array $rules масив з правилами валідації
     */
    public function validate($data, $rules=[]) {
        // Дані з $_POST
        $this->data = $data;
        if(file_exists(__DIR__ . "/rules/" . $data['submit'])){
            $this->rules = include(__DIR__ . "/rules/" . $data['submit']);
        }

        $validationRules = array_merge($this->rules, $rules);
        
        foreach ($validationRules as $field => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $singleRule) {
                $this->applyRule($field, $singleRule);
            }
        }
        return $this->errors==0 ? true : false;

    }

    /**
     * Застосувати правило перевірки для поля
     * @param string $field поле для перевірки
     * @param string $rule правило перевірки
     */
    protected function applyRule($field, $rule) {
        $params = explode(':', $rule);
        $ruleName = array_shift($params);

        if (method_exists($this, $ruleName)) {
            call_user_func_array([$this, $ruleName], [$field, ...$params]);
        }
    }

    /** Перевірка наявності даних для обов'язкових полів */
    protected function required($field) {
        if (empty($this->data[$field])) {
            $this->addError($field, _('Field is required.'));
        }
    }
    /** Превірка правильності імені електронної пошти */
    protected function email($field) {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, _('Invalid email format.'));
        }
    }

    /**
     *  Превірка мінімальної довжини строки
     * @param string $field ім'я поля
     * @param int $lenght довжина строки
     */
    protected function min($field, $length) {
        if (strlen($this->data[$field]) < $length) {
            $this->addError($field, _("Field must be at least {$length} characters long."));
        }
    }
    /** Превірка максимально довжини строки
     * @param string $field ім'я поля
     * @param int $lenght довжина строки
     */
    protected function max($field, $length) {
        if (strlen($this->data[$field]) > $length) {
            $this->addError($field, _("Field must not exceed {$length} characters."));
        }
    }


    protected function addError($field, $message) {
        $this->errors++;
        $this->cookie::setCookie("errors",json_encode([$field=>$message]));
    }
}

?>