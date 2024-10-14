<?php
namespace App\CakephpUpload;

use Cake\ORM\Entity;
use Cake\ORM\Table;
use Josegonzalez\Upload\File\Path\Basepath\DefaultTrait as BasepathTrait;
use Josegonzalez\Upload\File\Path\ProcessorInterface;
use Cake\I18n\Time;

class AreaAtuacaoNameFileProcessor implements ProcessorInterface
{
    /**
     * Table instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $table;

    /**
     * Entity instance.
     *
     * @var \Cake\ORM\Entity
     */
    protected $entity;

    /**
     * Array of uploaded data for this field
     *
     * @var array
     */
    protected $data;

    /**
     * Name of field
     *
     * @var string
     */
    protected $field;

    /**
     * Settings for processing a path
     *
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table  $table the instance managing the entity
     * @param \Cake\ORM\Entity $entity the entity to construct a path for.
     * @param array            $data the data being submitted for a save
     * @param string           $field the field for which data will be saved
     * @param array            $settings the settings for the current field
     */
    public function __construct(Table $table, Entity $entity, $data, $field, $settings)
    {        
        $this->table = $table;
        $this->entity = $entity;
        $this->data = $data;
        $this->field = $field;
        $this->settings = $settings;
    }

    use BasepathTrait;
    
    public function filename()
    {
        $ext = pathinfo($this->data['name'], PATHINFO_EXTENSION);        
        $nomeArquivo = Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');
        
        $nomeArquivo = str_replace([' ',':','-'], '_', $nomeArquivo);
        $nomeArquivo = $nomeArquivo.'.'.$ext;
        $this->data['name'] = $nomeArquivo;

        return $this->data['name'];
    }
}
