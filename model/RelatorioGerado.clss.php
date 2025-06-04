<?php
/**
 * RelatorioGerado Active Record
 * @author  <your-name-here>
 */
class RelatorioGerado extends TRecord
{
    const TABLENAME = '"public"."relatorio_gerado"';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    private $gerado_por;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
        parent::addAttribute('gerado_por');
        parent::addAttribute('periodo_inicio');
        parent::addAttribute('periodo_fim');
        parent::addAttribute('data_geracao');
    }


    public function set_gerado_por(Usuario $usuario)
    {
        $this->gerado_por = $usuario;
        $this->gerado_por = $usuario->id;
    }
    
    public function get_gerado_por()
    {
        if (empty($this->gerado_por))
            $this->gerado_por = new Usuario($this->gerado_por);
        return $this->gerado_por;
    }
}
