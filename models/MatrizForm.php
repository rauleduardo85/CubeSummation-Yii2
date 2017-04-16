<?php

namespace app\models;

use Yii;
use yii\base\Model;

class MatrizForm extends Model
{
    
    public $input;
    public $output;
    
    public function rules() {
        return [
            [['input'], 'required'],
            [['output'], 'string', 'length' => ['min' => 1], 'skipOnEmpty' => true],
            [['input'], 'string', 'length' => ['min' => 20]]
        ];
    }
    
    public function attributeLabels() {
        return [
            'input' => 'Entrada',
            'output' => 'Salida'
        ];
    }
    
}

