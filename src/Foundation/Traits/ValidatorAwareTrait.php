<?php

namespace Chaos\Foundation\Traits;

/**
 * Trait ValidatorAwareTrait (currently not in use)
 * @author ntd1712
 */
trait ValidatorAwareTrait
{
    private static $bg2b5hbxg6 = [];

    /**
     * Return true if and only if $value passes all validations in the chain.
     *
     * @return  boolean|array An array of errors, FALSE otherwise.
     */
    public function isValid()
    {
        if (empty(self::$bg2b5hbxg6)) {
            return false; // no rules; defaults to FALSE
        }

        // initialize filter & validator
        $filters = [
            'php' => filter_list(),
            'validated' => [],
            'filter' => [],
            'filter_plugin' => null,
            'validator' => [],
            'validator_plugin' => null
        ];

        if (class_exists(ZEND_STATIC_VALIDATOR)) {
            $filters['validator_plugin'] = forward_static_call([ZEND_STATIC_VALIDATOR, 'getPluginManager']);
            $filters['validator'] = $filters['validator_plugin']->getRegisteredServices()['invokableClasses'];
        }

        if (class_exists(ZEND_STATIC_FILTER)) {
            $filters['filter_plugin'] = forward_static_call([ZEND_STATIC_FILTER, 'getPluginManager']);
            $filters['filter'] = $filters['filter_plugin']->getRegisteredServices()['invokableClasses'];
        }

        foreach (self::$bg2b5hbxg6 as $k => $v) {
            /** @var \ReflectionProperty[] $v */
            $newValue = $value = $v['property']->getValue($this);
            $hasValue = !isBlank($value);

            foreach ($v['rules'] as $rule) {
                // e.g. [full_special_chars('flags' => FILTER_FLAG_NO_ENCODE_QUOTES)]
                //      [HtmlEntities('quotestyle' => ENT_QUOTES, 'encoding' => 'UTF-8', 'doublequote' => true)]
                //      [StringLength('max' => 255, 'message' => 'Hi, ntd1712')]
                preg_match(CHAOS_MATCH_RULE_ITEM, $rule, $matches);

                if (!isset($matches[1])) { // e.g. full_special_chars, HtmlEntities, StringLength
                    continue;
                }

                $rule = strtolower($matches[1]);
                $options = [];

                if (!empty($matches[2])) { // e.g. ('max' => 255, 'message' => 'Hi, ntd1712')
                    $result = @eval('return array' . $matches[2] . ';');

                    if (null === error_get_last() && is_array($result)) {
                        $options = $result;

                        if (isset($options['message'])) {
                            $options['message'] = str_replace('{property}', $k, $options['message']);
                        }
                    }
                }

                // validator
                if ('notempty' === $rule || $hasValue) {
                    if (in_array($rule, $filters['validator'], true)) {
                        /** @var \Zend\Validator\AbstractValidator $validator */
                        $validator = $filters['validator_plugin']->get($rule, $options);
                        $filters['validated'][$rule] = true;

                        if (!$validator->isValid($value)) {
                            return $validator->getMessages();
                        }
                    } else if (in_array($rule, $filters['php'], true)) {
                        $result = filter_var($value, filter_id($rule), $options);
                        $filters['validated'][$rule] = true;

                        if (false === $result) {
                            return [
                                sprintf(
                                    'Value of "%s" is not valid for "%s"',
                                    32 < strlen($value) ? substr($value, 0, 20) . '...' : $value,
                                    $k
                                )
                            ];
                        }
                    }
                }

                // filter
                if (!isset($filters['validated'][$rule]) && $hasValue) {
                    if (in_array($rule, $filters['filter'], true)) {
                        /** @var \Zend\Filter\AbstractFilter $filter */
                        $filter = $filters['filter_plugin']->get($rule, $options);
                        $newValue = $filter->filter($value);
                    } else if (in_array($rule, $filters['php'], true)) {
                        $result = filter_var($value, filter_id($rule), $options);

                        if (false !== $result) {
                            $newValue = $result;
                        }
                    }

                    // set new property value (if any)
                    if ($value != $newValue) {
                        $v['property']->setValue($this, $newValue);
                    }
                }
            }
        }

        return false;
    }

    /**
     * Parse property filter & validator rules.
     *
     * @param   \ReflectionProperty $property The class property.
     * @return  static
     */
    protected function addRules(\ReflectionProperty $property)
    {
        $getter = 'get' . $property->name . 'DataRules'; // check if getXyzDataRules() method exists

        if (method_exists($this, $getter)) {
            return $this->addRule($property, @call_user_func([$this, $getter]));
        }

        preg_match_all(CHAOS_MATCH_RULES, $property->getDocComment(), $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $v) {
                // e.g. [NotEmpty|StringLength('max' => 255, 'message' => '{property} too long')]
                $rules = explode('|', $v);

                foreach ($rules as $rule) {
                    $this->addRule($property, '[' . trim($rule, " \t\n\r\0\x0B[]") . ']');
                }
            }
        }

        return $this;
    }

    /**
     * Add a rule to the end of the chain.
     *
     * @param   \ReflectionProperty $property The class property.
     * @param   string $rule The rule.
     * @return  static
     */
    private function addRule(\ReflectionProperty $property, $rule)
    {
        $name = $property->name;

        if (!array_key_exists($name, self::$bg2b5hbxg6)) {
            self::$bg2b5hbxg6[$name] = ['property' => $property, 'rules' => [$rule]];
        } else if (!in_array($rule, self::$bg2b5hbxg6[$name]['rules'], true)) {
            self::$bg2b5hbxg6[$name]['rules'][] = $rule;
        }

        return $this;
    }
}
