<?php
/**
 * ChamadoForm Form
 * @author  <your name here>
 */
class ChamadoForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Chamado');
        $this->form->setFormTitle('Chamado');
        

        // create the form fields
        $id = new TEntry('id');
        $descricao = new TText('descricao');
        $localizacao = new TEntry('localizacao');
        $usuario_id = new TDBUniqueSearch('usuario_id', 'manutsmart', 'Usuario', 'id', 'nome');
        $usuario_temporario = new TEntry('usuario_temporario');
        $protocolo = new TEntry('protocolo');
        $status = new TEntry('status');
        $tipo_id = new TEntry('tipo_id');
        $setor_id = new TDBUniqueSearch('setor_id', 'manutsmart', 'Setor', 'id', 'nome');
        $tecnico_id = new TEntry('tecnico_id');
        $criado_em = new TEntry('criado_em');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Descricao') ], [ $descricao ] );
        $this->form->addFields( [ new TLabel('Localizacao') ], [ $localizacao ] );
        $this->form->addFields( [ new TLabel('Usuario Id') ], [ $usuario_id ] );
        $this->form->addFields( [ new TLabel('Usuario Temporario') ], [ $usuario_temporario ] );
        $this->form->addFields( [ new TLabel('Protocolo') ], [ $protocolo ] );
        $this->form->addFields( [ new TLabel('Status') ], [ $status ] );
        $this->form->addFields( [ new TLabel('Tipo Id') ], [ $tipo_id ] );
        $this->form->addFields( [ new TLabel('Setor Id') ], [ $setor_id ] );
        $this->form->addFields( [ new TLabel('Tecnico Id') ], [ $tecnico_id ] );
        $this->form->addFields( [ new TLabel('Criado Em') ], [ $criado_em ] );

        $descricao->addValidation('Descricao', new TRequiredValidator);
        $localizacao->addValidation('Localizacao', new TRequiredValidator);
        $usuario_id->addValidation('Usuario Id', new TRequiredValidator);
        $setor_id->addValidation('Setor Id', new TRequiredValidator);
        $tecnico_id->addValidation('Tecnico Id', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $descricao->setSize('100%');
        $localizacao->setSize('100%');
        $usuario_id->setSize('100%');
        $usuario_temporario->setSize('100%');
        $protocolo->setSize('100%');
        $status->setSize('100%');
        $tipo_id->setSize('100%');
        $setor_id->setSize('100%');
        $tecnico_id->setSize('100%');
        $criado_em->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('manutsmart'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Chamado;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('manutsmart'); // open a transaction
                $object = new Chamado($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
