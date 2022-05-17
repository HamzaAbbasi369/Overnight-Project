<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

/**
 * Class Ong_Validation
 */
class ONG_Addon_Product_Validation
{

    private $args;

    const VALIDATION_RESULT_FIELD = 'checks';
    const OK_STATUS = 'OK';

    public function __construct(&$assoc_args)
    {
        $rule_args = [
            'rules' => [],
            'rule'  => null
        ];
        foreach (['rules', 'rule'] as $key) {
            if (isset($assoc_args[ $key ])) {
                $rule_args[ $key ] = $assoc_args[ $key ];
                unset($assoc_args[ $key ]);
            }
        }
        if (!is_array($rule_args['rules'])) {
            $rule_args['rules'] = explode(',', $rule_args['rules']);
        }
        $rule_args['rules'] = array_map('trim', $rule_args['rules']);
        $this->args = $rule_args;
    }

    /**
     * Magic getter for arguments.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->args[ $key ];
    }

    /**
     * Validate multiple items according to the list of rules.
     *
     * @param array $items
     *
     * @return array|mixed
     */
    public function validate($items)
    {
        $results = [];
        if ($this->args['rule']) {
            $results = $this->validateSingleRule($items, $this->args['rule']);
        } else {
            foreach ($items as $item) {
                if ($item && !empty($this->args['rules'])) {
                    $all_checks_passed = true;
                    $errors = [];
                    foreach ($this->args['rules'] as &$rule) {
                        $ruleChecker = Ong_String_Helper::underscore('Ong_Addon_Product_Check_'.ucwords($rule).'_Rule');
                        $ruleCheckerInstance = new $ruleChecker;
                        try {
                            $this->validateItemByRule($item, $ruleCheckerInstance);
                            $item['validation'][$rule] = self::OK_STATUS;
                        } catch (\InvalidArgumentException $e) {
                            $all_checks_passed = false;
                            $item['validation'][$rule] = $e->getMessage();
                        }
                    }
                    if (!$all_checks_passed) {
                        array_unshift($results, $item);
                    }
                }
            }
        }
        return $results;
    }

    private function validateSingleRule($items, ONG_Addon_Product_Validation_Rule_Interface $ruleChecker)
    {
        return $items;
    }

    /**
     * @param                                              $item
     * @param ONG_Addon_Product_Validation_Rule_Interface  $ruleChecker
     *
     * @return bool
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function validateItemByRule($item, ONG_Addon_Product_Validation_Rule_Interface $ruleChecker)
    {
        $ruleChecker->check($item);
        return true;
    }
}
