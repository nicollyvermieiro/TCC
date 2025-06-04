<?php
/**
 * Cargo Active Record
 * @author  <your-name-here>
 */
class Cargo extends TRecord
{
    const TABLENAME = '"public"."cargo"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }


}
