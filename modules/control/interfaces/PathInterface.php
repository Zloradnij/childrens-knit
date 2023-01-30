<?php

namespace app\modules\control\interfaces;

interface PathInterface
{
    public function getBasePath();

    public function getUploadDocumentRoot();

    public function getOriginalFolder();

    public function getBigFolder();

    public function getSmallFolder();

    public function getBigPath();

    public function getSmallPath();

}


