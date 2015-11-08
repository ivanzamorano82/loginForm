<?php

namespace App\Util;


/**
 * Describes class that validates specified params.
 */
class Validator
{
    /**
     * Params that must be validated.
     *
     * @var \App\Util\Params
     */
    protected $params = [];

    /**
     * Rules of validation for each param.
     * 
     * @var array
     */
    protected $rules = [];

    /**
     * List of errors occurred during validation of params.
     * 
     * @var array
     */
    protected $errors = [];

    /**
     * List of files downloaded via form.
     *
     * @var array
     */
    private $files = [];


    /**
     * List of additional validation methods.
     * 
     * @var array
     */
    protected $additionalChecks = [];

    /**
     * Error messages mapping in format:  error code => error message.
     * Message can contain placeholders in format "{([^{]*)('+i+')([^}]*)}"
     * for replacing to some value,
     * where "i" - number of replaceable placeholder.
     *
     * @var array
     */
    public $errorMessages = [
        'required' => 'Обязательное поле для заполнения',
        'alphabet' => 'Поле должно содержать только {"0"} буквы',
        'alphaNumeric' => 'Поле должно содержать только {"0"} буквы либо цифры',
        'numeric' => 'Поле должно содержать только числа',
        'matchWith' => 'Не совпадает с полем {0}',
        'length' => 'Не более {0} символов',
        'range' => 'Диапазон от {0} до {1} символов',
        'phone' => 'Телефон имеет некорректный формат',
        'email' => 'Email иммеет некорректный формат',
        'enum' => 'Недопустимый набор элементов',
        'match' => 'Не соответствует заданному паттерну {0}',
        'fileSizeB64' => 'Требуется {1} с размером не более {0} Мб'
    ];


    /**
     * Creates new validator.
     *
     * @param array $rules                   List of rules.
     * @param \App\Util\Params $params   Params that must be validated.
     */
    public function __construct($rules, $params)
    {
        if (!empty($rules)) {
            $this->rules = $rules;
        }
        $this->params = $params;
    }

    /**
     * Returns full list of errors occurred during validation.
     *
     * @return array   List of errors.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns error message by its error code.
     *
     * @param string $error   Error code corresponding to key of passed param.
     *
     * @return string   Error message.
     */
    public function getErrorMessage($error)
    {
        return isset($this->errorMessages[$error])
            ? $this->errorMessages[$error] : 'Неизвестная ошибка';
    }

    /**
     * Cleans the potentially malicious string.
     *
     * @param string $str   String that must be validated.
     *
     * @return string   Cleaned string.
     */
    public function clearXSS($str)
    {
        if (empty($str)) {
            return $str;
        }
        return str_replace(['<', '>'], ['‹', '›'], $str);
    }

    /**
     * Cleans current params from available XSS scripts and deletes not
     * identified params in rules.
     */
    public function clearParamsFromXSS()
    {
        foreach ($this->params->getAll() as $key => $value) {
            if (array_key_exists($key, $this->rules)) {
                if (is_array($value)) {
                    $self = $this;
                    array_walk($value, function (&$item) use (&$self) {
                        $item = $self->clearXSS($item);
                    });
                }
                $this->params->Set($key, $this->clearXSS($value));
            } else {
                $this->params->Del($key);
            }
        }
    }

    /**
     * Performs validation of passed parameters.
     *
     * @param bool $clearXSS   Required cleaning passed params of available
     *                         XSS scripts or not.
     *
     * @return bool   Result of validation.
     */
    public function check($clearXSS = true)
    {
        if ($clearXSS) {
            $this->clearParamsFromXSS();
        }
        // Add field in params if it is in rules
        // but it is not in incoming params.
        foreach ($this->rules as $field => $validators) {
            if (!array_key_exists($field, $this->params->getAll())) {
                $this->params->Set($field, null);
            }
        }
        // Processing of all params.
        foreach ($this->params->getAll() as $field => $value) {
            if (array_key_exists($field, $this->rules)) {
                foreach ($this->rules[$field] as $rule) {
                    preg_match('/^([\w\d]+)(\((.+)\))?$/', $rule, $matches);
                    $rule = $matches[1];
                    $arguments = [];
                    if (isset($matches[3])) {
                        if (preg_match('/^re:/', $matches[3])) {
                            $arguments = [str_replace('re:', '', $matches[3])];
                        } else {
                            $arguments = explode(',', $matches[3]);
                            array_walk($arguments, function (&$arg) {
                                $arg = trim($arg, '"');
                            });
                        }
                    }
                    $method = 'is'.ucfirst($rule);
                    if (method_exists(__CLASS__, $method)
                        || isset($this->additionalChecks[$method])
                    ) {
                        $validator = method_exists(__CLASS__, $method)
                            ? [__CLASS__, $method]
                            : $this->additionalChecks[$method];
                        if (!call_user_func($validator, $value, $arguments)) {
                            $this->errors[$field][$rule] = $this->sprintf(
                                $this->getErrorMessage($rule), $arguments
                            );
                        }
                    } else {
                        $this->errors[$field][$rule] = 'Неверный валидатор';  // TODO: error code needed
                    }
                }
            } else {
                $this->errors[$field]['common'] = 'Недопустимое поле';  // TODO: error code needed
            }
        }
        return !$this->hasErrors();
    }

    /**
     * Checks that validator has errors or not.
     *
     * @return bool   Exists errors or not.
     */
    public function hasErrors ()
    {
        return !empty($this->errors);
    }

    /**
     * Checks that passed value is not empty.
     *
     * @param string $value      Value that must be validated.
     *
     * @return bool   Result of validation.
     */
    public function isRequired($value)
    {
        return ((is_string($value) && mb_strlen($value) > 0)
                || (is_array($value) && count($value))
        );
    }

    /**
     * Checks that passed value (string) contains only alphabetical symbols
     * or spaces or underscores given the language of string (russian - "ru"
     * or english - "en").
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - is allowed language if it is
     *                           defined. Otherwise russian and english language
     *                           are allowed.
     *
     * @return int   Result of validation.
     */
    public function isAlphabet($value, $arguments = [])
    {
        $lang = isset($arguments[0]) ? $arguments[0] : null;
        if ($lang === 'ru') {
            $regExp = '/^[а-яА-Я_\s]*$/ui';
        } elseif ($lang === 'en') {
            $regExp = '/^[a-zA-Z_\s]*$/i';
        } else {
            $regExp = '/^[a-zA-Zа-яА-Я_\s]*$/ui';
        }
        return preg_match($regExp, $value);
    }

    /**
     * Checks that passed value (string) contains only alphabetical or numeric
     * symbols or spaces or underscores given the language of string
     * (russian - "ru" or english - "en").
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - is allowed language if it is
     *                           defined. Otherwise russian and english language
     *                           are allowed.
     *
     * @return int   Result of validation.
     */
    public function isAlphaNumeric($value, $arguments = [])
    {
        $locale = isset($arguments[0]) ? $arguments[0] : null;
        if ($locale === 'ru') {
            $regExp = '/^[а-яА-Я_\s\d]*$/ui';
        } elseif ($locale === 'en') {
            $regExp = '/^[a-zA-Z_\s\d]*$/i';
        } else {
            $regExp = '/^[a-zA-Zа-яА-Я_\s\d]*$/ui';
        }
        return preg_match($regExp, $value);
    }

    /**
     * Checks that passed value contains only numeric symbols
     * or spaces or points.
     *
     * @param string $value      Value that must be validated.
     *
     * @return int   Result of validation.
     */
    public function isNumeric($value)
    {
        return preg_match('/^[\d\s\.]*$/', $value);
    }

    /**
     * Checks that length of passed value isn't longer of allowed length.
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - is allowed length of string..
     *
     * @return bool   Result of validation.
     */
    public function isLength($value, $arguments = [])
    {
        if (!isset($arguments[0])) {
            return false;
        }
        return (mb_strlen($value) <= (int)$arguments[0]);
    }

    /**
     * Checks for valid length of passed value in specified range.
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - start of allowed range,
     *                           second - end of allowed range.
     *
     * @return bool   Result of validation.
     */
    public function isRange($value, $arguments = [])
    {
        if (!isset($arguments[0]) || !isset($arguments[1])) {
            return false;
        }
        return ((mb_strlen($value) >= (int)$arguments[0]
                 && mb_strlen($value) <= (int)$arguments[1])
                || empty($value)
        );
    }

    /**
     * Checks that passed value matches with value of another param.
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - is key of another param.
     *
     * @return bool   Result of validation.
     */
    public function isMatchWith($value, $arguments = [])
    {
        if (!isset($arguments[0])) {
            return false;
        }
        return (
            $this->params->exists($arguments[0])
            && $value === $this->params->String($arguments[0])
        ) ? true : false;
    }

    /**
     * Checks that passed value matches to the specified regular expression.
     *
     * @param string $value      Value that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           First argument - is regular expression.
     *
     * @return bool   Result of validation.
     */
    public function isMatch($value, $arguments = [])
    {
        if (!isset($arguments[0])) {
            return false;
        }
        return preg_match($arguments[0], $value);
    }

    /**
     * Checks for valid email.
     *
     * @param string $value   Value that must be validated.
     *
     * @return bool   Result of validation.
     */
    public function isEmail($value)
    {
        if ($value === '') {
            return true;
        };
        return (false === filter_var($value, FILTER_VALIDATE_EMAIL))
            ? false : true;
    }

    /**
     * Checks that passed value is array and matches to passed set of values.
     *
     * @param array $value       Array of elements that must be validated.
     * @param array $arguments   Additional arguments required for validation.
     *                           All arguments define set of allowed values.
     *
     * @return bool              Result of validation.
     */
    public function isEnum($value, $arguments = [])
    {
        if (!is_array($value)) {
            return true;
        }
        return empty(array_diff($value, $arguments)) ? true : false;
    }

    /**
     * Checks for valid phone.
     *
     * @param string $value   Value that must be validated.
     *
     * @return bool   Result of validation.
     */
    public function isPhone($value)
    {
        if ($value === '') {
            return true;
        }
        $regExp = '/^[\d\s\-\(\)\+]{10,20}$/'; //+38 (000) 000-00-00
        return preg_match($regExp, $value);
    }

    /**
     * Checks for valid type and size of file in base64 format.
     *
     * @param string $file       File in base64 format.
     * @param array $arguments   Additional arguments required for validation.
     *                           The first argument - is type of file,
     *                           the second argument - is maximal allowed size
     *                           of file.
     *
     * @return bool              Result of validation.
     */
    public function isFileSizeB64($file, $arguments = [])
    {
        if (empty($file)) {
            return true;
        }
        $file = explode(';base64,', $file);
        $fileInfo  = $file[0];
        $fileData = $file[1];
        $data = base64_decode($fileData);
        $regExp = '/^data:(.+)\/(.+)$/';

        $fileSize = round($arguments[0] * 1024 * 1204, 1);
        $fileType = $arguments[1];

        if (preg_match($regExp, $fileInfo, $matches)
           && $matches[1] == $fileType
           && strlen($data) <= $fileSize
        ) {
            $fileExt = $matches[2];
            $file_name = uniqid() .'.'.$fileExt;
            $this->files[] = ['file_name' => $file_name, 'data' => $data];
            return true;
        }
        return false;
    }

    /**
     * Returns formatted string.
     *
     * @param string $str        String that must be formatted.
     * @param array $arguments   Arguments for replacing of placeholders.
     *
     * @return string   Formatted string filled passed arguments.
     */
    protected function sprintf($str, $arguments = [])
    {
        if (empty($arguments)) {
            return preg_replace('/{[^{]*\d+[^}]*}/', '', $str);
        }
        for ($i = 0; $i < count($arguments); $i++) {
            $str = preg_replace(
                '/{([^{]*)('.$i.')([^}]*)}/',
                '${1}'.$arguments[$i].'${3}',
                $str
            );
        }
        return $str;
    }

    /**
     * Add new additional method of validation with own error message.
     *
     * @param string $methodName   Name of validation method.
     * @param callable $function   Function with main logic of validation.
     * @param string $message      Error message that will be shown in
     *                             error case.
     */
    public function addCheckMethod($methodName, $function, $message = '')
    {
        $this->additionalChecks['is'.ucfirst($methodName)] = $function;
        if (!empty($message)) {
            $this->errorMessages[$methodName] = $message;
        }
    }
}
