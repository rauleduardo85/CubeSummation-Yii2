<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'input')->textarea(['cols' => '50' , 'rows' => '10']); ?>
    
    

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

<div class="row">
    <div class="col-lg-12">
        <?php
        echo '<textarea cols="50" rows="10" readonly="readonly" style="white-space: pre-line;" wrap="hard">';
        if(isset($model->output))
        {
            foreach ($model->output as $md)
            {
                echo $md."\n";
            }
        }
        echo '</textarea>';
        
        ?>
    </div>
</div>


