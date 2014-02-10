<?php

/**
 * This is the model class for table "profile_field".
 *
 * The followings are the available columns in table 'profile_field':
 * @property integer $id
 * @property integer $profile_field_category_id
 * @property string $module_id
 * @property string $field_type_class
 * @property string $field_type_config
 * @property string $internal_name
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 * @property integer $required
 * @property integer $visible
 * @property integer $editable
 * @property integer $show_at_registration
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @package humhub.modules_core.user.models
 * @since 0.5
 */
class ProfileField extends HActiveRecord {

    /**
     * Field Type Instance
     *
     * @var type
     */
    private $_fieldType = null;

    /**
     * Default Value for Sort Order
     *
     * @var Integer
     */
    public $sort_order = 100;

    /**
     * Default Value for Visibile
     *
     * @var Integer
     */
    public $visible = 1;

    /**
     * Default Value for Editable
     *
     * @var Integer
     */
    public $editable = 1;

    /**
     * Default Value for Show At Registration
     *
     * @var Integer
     */
    public $show_at_registration = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProfileField the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'profile_field';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('profile_field_category_id, field_type_class, internal_name, title, sort_order', 'required'),
            array('profile_field_category_id, required, editable,show_at_registration,  visible, sort_order, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('module_id, field_type_class, title', 'length', 'max' => 255),
            array('internal_name', 'length', 'max' => 100),
            array('internal_name', 'checkInternalName'),
            array('internal_name', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9_]/', 'message' => Yii::t('UserModule.base', 'Only alphanumeric characters allowed!')),
            array('field_type_class', 'checkType'),
            array('description, created_at, updated_at', 'safe'),
        );

        return $rules;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'ProfileFieldCategory', 'profile_field_category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('base', 'ID'),
            'profile_field_category_id' => Yii::t('UserModule.profile', 'Profile Field Category'),
            'module_id' => Yii::t('UserModule.profile', 'Module'),
            'field_type_class' => Yii::t('UserModule.profile', 'Fieldtype'),
            'field_type_config' => Yii::t('UserModule.profile', 'Type Config'),
            'internal_name' => Yii::t('UserModule.profile', 'Internal Name'),
            'visible' => Yii::t('UserModule.profile', 'Visible'),
            'editable' => Yii::t('UserModule.profile', 'Editable'),
            'show_at_registration' => Yii::t('UserModule.profile', 'Show at registration'),
            'required' => Yii::t('base', 'Required'),
            'title' => Yii::t('base', 'Title'),
            'description' => Yii::t('base', 'Description'),
            'sort_order' => Yii::t('base', 'Sort order'),
            'created_at' => Yii::t('base', 'Created at'),
            'created_by' => Yii::t('base', 'Created by'),
            'updated_at' => Yii::t('base', 'Updated at'),
            'updated_by' => Yii::t('base', 'Updated by'),
        );
    }

    /**
     * Before deleting a profile field, inform underlying ProfileFieldType for
     * cleanup.
     */
    public function beforeDelete() {
        $this->fieldType->delete();
        return parent::beforeDelete();
    }

    /**
     * After Save, also saving the underlying Field Type
     */
    public function afterSave() {

        # Cause Endless
        #$this->fieldType->save();
        return parent::afterSave();
    }

    /**
     * Returns the ProfileFieldType Class for this Profile Field
     *
     * @return ProfileFieldType
     */
    public function getFieldType() {

        if ($this->_fieldType != null)
            return $this->_fieldType;

        if ($this->field_type_class != "") {
            $type = $this->field_type_class;
            $this->_fieldType = new $type;
            $this->_fieldType->setProfileField($this);
            return $this->_fieldType;
        }
        return null;
    }

    /**
     * Returns The Form Definition to edit the ProfileField Model.
     *
     * @return Array CForm Definition
     */
    public function getFormDefinition() {

        $categories = ProfileFieldCategory::model()->findAll(array('order' => 'sort_order'));
        $definition = array(
            'ProfileField' => array(
                'type' => 'form',
                #'showErrorSummary' => true,
                'elements' => array(
                    'internal_name' => array(
                        'type' => 'text',
                        'maxlength' => 32,
                        'class' => 'form-control',
                    ),
                    'title' => array(
                        'type' => 'text',
                        'maxlength' => 32,
                        'class' => 'form-control',
                    ),
                    'description' => array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                    ),
                    'sort_order' => array(
                        'type' => 'text',
                        'maxlength' => 32,
                        'class' => 'form-control',
                    ),
                    'required' => array(
                        'type' => 'checkbox',
                    ),
                    'visible' => array(
                        'type' => 'checkbox',
                    ),
                    'show_at_registration' => array(
                        'type' => 'checkbox',
                    ),
                    'editable' => array(
                        'type' => 'checkbox',
                    ),
                    'profile_field_category_id' => array(
                        'type' => 'dropdownlist',
                        'items' => CHtml::listData($categories, 'id', 'title'),
                        'class' => 'form-control',
                    ),
                    'field_type_class' => array(
                        'type' => 'dropdownlist',
                        'items' => ProfileFieldType::getFieldTypes(),
                        'class' => 'form-control',
                    ),
                )
        ));

        // Field Type and Internal Name cannot be changed for existing records
        // So disable these fields.
        if (!$this->isNewRecord) {
            $definition['ProfileField']['elements']['field_type_class']['disabled'] = true;
            $definition['ProfileField']['elements']['internal_name']['readonly'] = true;
        }
        return $definition;
    }

    /**
     * Validator which checks the given internal name.
     *
     * Also ensures that internal_name could not be changed on existing records.
     */
    public function checkInternalName() {

        // Little bit cleanup
        $this->internal_name = strtolower($this->internal_name);
        $this->internal_name = trim($this->internal_name);

        if (!$this->isNewRecord) {

            // Dont allow changes of internal_name - Maybe not the best way to check it.
            $currentProfileField = ProfileField::model()->findByPk($this->id);
            if ($this->internal_name != $currentProfileField->internal_name) {
                $this->addError('internal_name', Yii::t('UserModule.profile', 'Internal name could not be changed!'));
            }
        } else {

            // Check if Internal Name is not in use yet
            $table = Yii::app()->getDb()->getSchema()->getTable(Profile::model()->tableName());
            $columnNames = $table->getColumnNames();
            if (in_array($this->internal_name, $columnNames)) {
                $this->addError('internal_name', Yii::t('UserModule.profile', 'Internal name already in use!'));
            }
        }
    }

    /**
     * Validator which checks the fieldtype
     *
     * Also ensures that field_type_class could not be changed on existing records.
     */
    public function checkType() {

        if (!$this->isNewRecord) {

            // Dont allow changes of internal_name - Maybe not the best way to check it.
            $currentProfileField = ProfileField::model()->findByPk($this->id);
            if ($this->field_type_class != $currentProfileField->field_type_class) {
                $this->addError('field_type_class', Yii::t('UserModule.profile', 'Field Type could not be changed!'));
            }
        } else {
            if (!key_exists($this->field_type_class, ProfileFieldType::getFieldTypes())) {
                $this->addError('field_type_class', Yii::t('UserModule.profile', 'Invalid field type!'));
            }
        }
    }

    /**
     * Returns the users value for this profile field.
     *
     * @param type $user
     * @param type $raw
     *
     * @return type
     */
    public function getUserValue($user, $raw = true) {
        return $this->fieldType->getUserValue($user, $raw);
    }

}
