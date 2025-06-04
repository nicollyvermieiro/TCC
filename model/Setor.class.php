<?php
/**
 * Setor Active Record
 * @author  <your-name-here>
 */
class Setor extends TRecord
{
    const TABLENAME = '"public"."setor"';
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
