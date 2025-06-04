<?php
/**
 * Chamado Active Record
 * @author  <your-name-here>
 */
class Chamado extends TRecord
{
    const TABLENAME = '"public"."chamado"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $usuario;
    private $tipo_chamado;
    private $setor;
    private $tecnico;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('localizacao');
        parent::addAttribute('usuario_id');
        parent::addAttribute('usuario_temporario');
        parent::addAttribute('protocolo');
        parent::addAttribute('status');
        parent::addAttribute('tipo_id');
        parent::addAttribute('setor_id');
        parent::addAttribute('tecnico_id');
        parent::addAttribute('criado_em');
    }

    
    /**
     * Method set_usuario
     * Sample of usage: $chamado->usuario = $object;
     * @param $object Instance of Usuario
     */
    public function set_usuario(Usuario $object)
    {
        $this->usuario = $object;
        $this->usuario_id = $object->id;
    }
    
    /**
     * Method get_usuario
     * Sample of usage: $chamado->usuario->attribute;
     * @returns Usuario instance
     */
    public function get_usuario()
    {
        // loads the associated object
        if (empty($this->usuario))
            $this->usuario = new Usuario($this->usuario_id);
    
        // returns the associated object
        return $this->usuario;
    }
    
    
    /**
     * Method set_tipo_chamado
     * Sample of usage: $chamado->tipo_chamado = $object;
     * @param $object Instance of TipoChamado
     */
    public function set_tipo_chamado(TipoChamado $object)
    {
        $this->tipo_chamado = $object;
        $this->tipo_chamado_id = $object->id;
    }
    
    /**
     * Method get_tipo_chamado
     * Sample of usage: $chamado->tipo_chamado->attribute;
     * @returns TipoChamado instance
     */
    public function get_tipo_chamado()
    {
        // loads the associated object
        if (empty($this->tipo_chamado))
            $this->tipo_chamado = new TipoChamado($this->tipo_chamado_id);
    
        // returns the associated object
        return $this->tipo_chamado;
    }
    
    
    /**
     * Method set_setor
     * Sample of usage: $chamado->setor = $object;
     * @param $object Instance of Setor
     */
    public function set_setor(Setor $object)
    {
        $this->setor = $object;
        $this->setor_id = $object->id;
    }
    
    /**
     * Method get_setor
     * Sample of usage: $chamado->setor->attribute;
     * @returns Setor instance
     */
    public function get_setor()
    {
        // loads the associated object
        if (empty($this->setor))
            $this->setor = new Setor($this->setor_id);
    
        // returns the associated object
        return $this->setor;
    }
    
    
    public function set_tecnico(Usuario $tecnico)
    {
        $this->tecnico = $tecnico;
        $this->tecnico_id = $tecnico->id;
    }
    
    public function get_tecnico()
    {
        if (empty($this->tecnico))
            $this->tecnico = new Usuario($this->tecnico_id);
        return $this->tecnico;
    }
    

}
