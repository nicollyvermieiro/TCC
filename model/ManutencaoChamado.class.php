<?php
/**
 * ManutencaoChamado Active Record
 * @author  <your-name-here>
 */
class ManutencaoChamado extends TRecord
{
    const TABLENAME = '"public"."manutencao_chamado"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $chamado;
    private $tecnico;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('chamado_id');
        parent::addAttribute('tecnico_id');
        parent::addAttribute('descricao_servico');
        parent::addAttribute('pecas_trocadas');
        parent::addAttribute('observacoes');
        parent::addAttribute('data_registro');
    }

    
    /**
     * Method set_chamado
     * Sample of usage: $manutencao_chamado->chamado = $object;
     * @param $object Instance of Chamado
     */
    public function set_chamado(Chamado $object)
    {
        $this->chamado = $object;
        $this->chamado_id = $object->id;
    }
    
    /**
     * Method get_chamado
     * Sample of usage: $manutencao_chamado->chamado->attribute;
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
    
    
    public function set_tecnico(Usuario $object)
    {
        $this->tecnico = $object;
        $this->tecnico_id = $object->id;
    }

    public function get_tecnico()
    {
        if (empty($this->tecnico))
            $this->tecnico = new Usuario($this->tecnico_id);
        return $this->tecnico;
    }
    


}
