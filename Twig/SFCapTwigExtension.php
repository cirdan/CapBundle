<?php

namespace SF\CAPBundle\Twig;

use SF\CapBundle\Entity\CapRunner;

class SFCapTwigExtension extends \Twig_Extension
{

    function __construct() {
    }

    public function getGlobals() {
        return array(
            'runner' => $this->get("user")
        );
    }

    public function getName()
    {
        return 'runner';
    }}
