<?php

namespace app\modules\catalogHeight\models\form;

class OfferTemplate
{
    public static function getInlineTemplate()
    {
        return '
            <div class="row">
                <div class="col-sm-4 text-right">{label}</div>
                <div class="col-sm-8">{input}</div>
                <div class="col-sm-12">{error}{hint}</div>
            </div>
        ';
    }
}