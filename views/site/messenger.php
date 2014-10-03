<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';


// register simple javascript which will periodicaly update online status for current user
$script = '
function updateMessenger() {
	//console.log("updating status - start ");
	$.get("'.Url::to(['update-online-status']).'");
	//	console.log("updating status - end");
}

//console.log("update messenger interval set");
setInterval(updateMessenger,5000);
';
$this->registerJs($script, View::POS_READY);
?>
<div class="site-index">

    <div class="body-content">
        <div class="row">
            <div class="col-lg-2">
                <h2>User list</h2>




            </div>
            <div class="col-lg-4">
                <h2>Messages</h2>




            </div>
        </div-->

    </div>
</div>
