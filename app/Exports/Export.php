<?php
namespace App\Exports;

abstract class Export{
    /**
     * excel cells
     * @var array
     */
    public $cells = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    /**
     * excel file name
     * @var
     */
    public $filename;


    public function __construct($filename)
    {
        $this->filename = $filename;

        $this->handle();

    }

    /**
     * handle
     */
    public function handle(){

    }

}
