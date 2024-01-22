<?php 

namespace app\core;


abstract class Model 
{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{ $key} = $value;
            }
        }
    }

    abstract public function rules() : array;
    
    public function validate($data)
    {
        
        foreach($this->rules() as $attributes => $rules){
            $value = $this->{ $attributes};
            foreach($rules as $rule){
                $rulename = $rule;
                if(!is_string($rulename)){
                    $rulename = $rule[0];
                }

                if($rulename === self::RULE_REQUIRED && !$value){
                    
                }
            }
        }
        
    }
}