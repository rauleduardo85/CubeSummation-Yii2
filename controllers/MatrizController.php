<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\MatrizForm;


class MatrizController extends Controller
{
    
    
    public function actionSubmit()
    {
        $model = new MatrizForm();
        
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            echo '11111';
            
            
        } else {            
            
            return $this->render('submit', ['model' => $model]);
        }   
    }
    
}

