<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\MatrizForm;
use app\models\MatrizOperations;

class MatrizController extends Controller
{
    
    
    public function actionSubmit()
    {
        $model = new MatrizForm();
        
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $matrizOp = new MatrizOperations();
            $datos = implode('/', $model->getAttributes(['input']));
            $resultado = $matrizOp->controlDatos($datos) ;
            $resultado = array_filter($resultado , 'strlen');
            $model->setAttributes(['output' => $resultado]);
        }             
            
        return $this->render('submit', ['model' => $model]);
          
    }
    
}

