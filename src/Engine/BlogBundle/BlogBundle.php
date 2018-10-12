<?php

namespace Engine\BlogBundle;

use Engine\BlogBundle\Admin\Admin;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlogBundle extends Bundle
{

    public static function getAdmin(){
        return new Admin();
    }
}
