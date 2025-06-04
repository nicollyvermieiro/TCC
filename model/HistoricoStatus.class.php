<?php
/**
 * HistoricoStatus Active Record
 * @author  <your-name-here>
 */
class HistoricoStatus extends TRecord
{
    const TABLENAME = '"public"."historico_status"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $chamado;
    private $alterado_por;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('chamado_id');
        parent::addAttribute('status_anterior');
        parent::addAttribute('novo_status');
        parent::addAttribute('alterado_por');
        parent::addAttribute('data_alteracao');
    }

    
    /**
     * Method set_chamado
     * Sample of usage: $historico_status->chamado = $object;
     * @param $object Instance of Chamado
     */
    public function set_chamado(Chamado $object)
    {
        $this->chamado = $object;
        $this->chamado_id = $object->id;
    }
    
    /**
     * Method get_chamado
     * Sample of usage: $historico_status->chamado->attribute;
     * @returns Chamado instance
     */
    public function get_chamado()
    {
        // loads the associated object
        if (empty($this->chamado))
            $this->chamado = new Chamado($this->chamado_id);
    
        // returns the associated object
        return $this->chamado;
    }
     
    public function set_alterado_por(Usuario $usuario)
    {
        $this->alterado_por = $usuario;
        $this->alterado_por = $usuario->id;
    }
    
    public function get_alterado_por()
    {
        if (empty($this->alterado_por))
            $this->alterado_por = new Usuario($this->alterado_por);
        return $this->alterado_por;
    }


}
