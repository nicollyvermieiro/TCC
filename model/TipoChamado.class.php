<?php
/**
 * TipoChamado Active Record
 * @author  <your-name-here>
 */
class TipoChamado extends TRecord
{
    const TABLENAME = '"public"."tipo_chamado"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
    }


}
