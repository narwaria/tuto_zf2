<?php 
// /module/src/MyModule/View/Helper/SpecialPurpose.php
namespace Dashboard\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SpecialPurpose extends AbstractHelper
{
    protected $count = 0;

    public function __invoke()
    {
        $this->count++;
        $output = sprintf("I have seen 'The Jerk' %d time(s).", $this->count);
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }
}