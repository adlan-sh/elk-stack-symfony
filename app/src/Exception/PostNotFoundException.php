<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class PostNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Пост не найден");
    }
}
