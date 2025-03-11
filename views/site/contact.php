<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>


<section>
    <div class="container my-5">
        <div class="breadcrumbs mb-3">
            <span>
                <a href="<?= Url::to(['site/index']) ?>" title=" Go to Neutron sys web" class="home">Home</a>
            </span>
            <span><i class="fa fa-angle-right"></i></span>
            <span>
                <?= Html::encode($this->title) ?>
            </span>
        </div>
        <div class="mb-5">
            <h1 class="page_title">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4">

                <div class="mb-5">
                    <h5>
                        Contact details
                    </h5>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="contact-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <?= $settings->address ?>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="contact-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <?= $settings->phone ?>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <a href="mailto:  <?= $settings->email ?>">
                            <?= $settings->email ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">


                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2990.274257380938!2d-78.795011!3d35.789228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e52963ac45bbcb%3A0xf05e8d125e82af10!2sDos%20Mas!5e0!3m2!1sen!2sus!4v1671220374408!5m2!1sen!2sus"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="mb-4">
                    <h5>
                        Please let us know if you have a question.
                    </h5>
                </div>
                <hr />
                <div class="mt-4">
                    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                        <div class="alert alert-success" role="alert">
                            Thank you for reaching out to us! Please be assured that we will do our best to respond promptly.
                            <?php if (Yii::$app->mailer->useFileTransport): ?>

                            <?php endif; ?>
                        </div>

                    <?php else: ?>


                        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>


                        <div class="form-group d-flex justify-content-end">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-lg', 'name' => 'contact-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>




                </div>

            </div>

        </div>
    </div>
</section>