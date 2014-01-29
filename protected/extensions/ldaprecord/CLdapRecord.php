<?php
/**
 *
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/**
 * CLdapRecord
 *
 * CLdapServer holds all the defined object classes.
 *
 * The used design pattern is Singleton. To get the one and
 * only instance of this class call CLdapServer::getInstance().
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
abstract class CLdapRecord extends CModel {
	const BELONGS_TO='CLdapBelongsTo';
	const HAS_ONE='CLdapHasOne';
	const HAS_MANY='CLdapHasMany';

	protected $_dn = null;						// DN of this node
	private $_readDn = null;					// set just one time after reading an entry from LDAP
	protected $_branchDn = '';					// DN of the parent node
	protected $_filter = array();				// possible filter; used by reading
	protected $_dnAttributes = array();			// attributes used to create DN; order important!
	protected $_objectClasses = array();		// allowed object classes
	private $_md;								// meta data
	protected $_attributes = null;				// array of actual attributes
	protected $_related = array();				// attribute name => related objects
	protected $_overwrite = false;				// overwrite existing attributes

	/**
	 * Constructor.
	 * @param string scenario name. See {@link CModel::scenario} for more details about this parameter.
	 */
	public function __construct($scenario='insert')	{
		$this->createAttributes();

		if($scenario === null) {
			return;
		}

		$this->setScenario($scenario);

		$this->init();

		$this->attachBehaviors($this->behaviors());
	}

	/**
	 * PHP getter magic method.
	 * This method is overridden so that attributes can be accessed like properties.
	 * @param string property name
	 * @return mixed property value
	 * @see getAttribute
	 */
	public function __get($name)
	{
		$retval = $this->getAttribute($name);
		if ($retval === false) {
			if(isset($this->_related[$name])) {
				return $this->_related[$name];
			}
			else if(isset($this->getMetaData()->relations[$name])) {
				return $this->getRelated($name);
			}
			else {
				return parent::__get($name);
			}
		}
		return $retval;
	}

	/**
	 * PHP setter magic method.
	 * This method is overridden so that attributes can be accessed like properties.
	 * @param string property name
	 * @param mixed property value
	 */
	public function __set($name, $value)
	{
		if(property_exists($this, $name)) {
			$this->$name=$value;
		}
		else if ($this->hasAttribute($name)) {
			$this->setAttribute($name, $value);
		}
		else if ($this->hasRelation($name)) {
			$this->_related[$name] = $value;
		}
		else {
			parent::__set($name, $value);
		}
	}

	/**
	 * Returns the list of all attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeNames() {
		return array_keys($this->_attributes);
	}

	/**
	 * Initializes this model.
	 * This method is invoked when an instance is newly created and has
	 * its {@link scenario} set.
	 * You may override this method to provide code that is needed to initialize the model (e.g. setting
	 * initial property values.)
	 */
	public function init() {
	}

	/**
	 * Returns the named attribute value.
	 * If this record is the result of a query and the attribute is not loaded,
	 * null will be returned.
	 * You may also use $this->AttributeName to obtain the attribute value.
	 * @param string the attribute name
	 * @return mixed the attribute value. Null if the attribute is not set or does not exist.
	 * @see hasAttribute
	 */
	public function getAttribute($name)
	{
		if(property_exists($this, $name)) {
			return $this->$name;
		}
		else if(isset($this->_attributes[$name])) {
			return $this->_attributes[$name]['value'];
		}
		else if(isset($this->_attributes[strtolower($name)])) {
			return $this->_attributes[strtolower($name)]['value'];
		}
		else if ('attributes' == $name) {
			$retval = array();
			foreach($this->_attributes as $name => $info) {
				$retval[$name] = $info['value'];
			}
			return $retval;
		}
		return false;
	}

	/**
	 * Sets the named attribute value.
	 * You may also use $this->AttributeName to set the attribute value.
	 * @param string the attribute name
	 * @param mixed the attribute value.
	 * @return boolean whether the attribute exists and the assignment is conducted successfully
	 * @throws CLdapException if an error occurs.
	 * @see hasAttribute
	 */
	public function setAttribute($name, $value, $always=false)
	{
		error_log("setAttribute($name, $value)");
		if(isset($this->_attributes[$name]) || isset($this->_attributes[strtolower($name)]) || '*' == $this->_objectClasses) {
			if(isset($this->_attributes[strtolower($name)])) {
				$name = strtolower($name);
			}
			switch($this->_attributes[$name]['type']) {
				case 'assozarray':
					preg_match($this->_attributes[$name]['typedata'], $value, $parts);
					if (3 != count($parts)) {
						throw new CLdapException(Yii::t('LdapComponent.record', 'Parse value from attr \'{name}\' failed! (Wrong reg pattern)'), array('{name}'=>$name));
					}
					$this->_attributes[$name]['value'][$parts[1]] = $parts[2];
					break;
				case 'array':
					if ($this->_overwrite) {
						$this->_attributes[$name]['value'] = array($value);
					}
					else {
						$this->_attributes[$name]['value'][] = $value;
					}
					break;
				default:
					if (isset($this->_attributes[$name]['value']) && !$this->_overwrite) {
						$this->_attributes[$name]['type'] = 'array';
						$firstvalue = $this->_attributes[$name]['value'];
						$this->_attributes[$name]['value'] = array($firstvalue, $value);
					}
					else {
						$this->_attributes[$name]['value'] = $value;
					}
					break;
			}

		}
		else if ('attributes' == $name && is_array($value)) {
			foreach ($value as $key => $val) {
				$this->$key = $val;
			}
		}
		else {
			$aliases = CLdapSchema::getInstance()->getAttributeType($name)->getNames();
			$ok = false;
			foreach($aliases as $alias) {
				if ($name == $alias) continue;
				if (isset($this->_attributes[$alias]) || isset($this->_attributes[strtolower($alias)])) {
					$ok = $this->setAttribute($alias, $value);
					break;
				}
			}
			if (!$ok) {
				return parent::__set($name, $value);
			}
		}
		return true;
	}

	/**
	 * Checks if a property value is null.
	 * This method overrides the parent implementation by checking
	 * if the named attribute is null or not.
	 * @param string the property name or the event name
	 * @return boolean whether the property value is null
	 */
	public function __isset($name)
	{
		if(isset($this->_attributes[$name])) {
			return true;
		}
		else {
			return parent::__isset($name);
		}
	}

	/**
	 * Returns the related record(s).
	 * This method will return the related record(s) of the current record.
	 * If the relation is HAS_ONE or BELONGS_TO, it will return a single object
	 * or null if the object does not exist.
	 * If the relation is HAS_MANY, it will return an array of objects
	 * or an empty array.
	 * @param string the relation name (see {@link relations})
	 * @param boolean whether to reload the related objects from database. Defaults to false.
	 * @param array additional parameters that customize the query conditions as specified in the relation declaration.
	 * @return mixed the related object(s).
	 * @throws CLdapException if an error occurs.
	 * @see hasRelated
	 */
	public function getRelated($name, $refresh=false, $params=array())
	{
		if(!$refresh && $params === array() && (isset($this->_related[$name]) || array_key_exists($name,$this->_related))) {
			return $this->_related[$name];
		}

		$md = $this->getMetaData();
		if(!isset($md->relations[$name])) {
			throw new CLdapException(Yii::t('LdapRecord.record','{class} does not have relation "{name}".',
			array('{class}'=>get_class($this), '{name}'=>$name)));
		}
		//Yii::trace('lazy loading '.get_class($this).'.'.$name,'system.db.ar.CActiveRecord');
		$relation = $md->relations[$name];
		//echo '<pre>' . print_r($relation, true) . '</pre>';
		if($this->isNewEntry() && ($relation instanceof CLdapHasOne || $relation instanceof CLdapHasMany)) {
			return $relation instanceof CLdapHasOne ? null : array();
		}
		if($params !== array()) {
			$exists = isset($this->_related[$name]) || array_key_exists($name, $this->_related);
			if($exists) {
				$save = $this->_related[$name];
			}
			unset($this->_related[$name]);
		}
		$this->_related[$name] = $relation->createRelationalRecord($this, $params);

		if(!isset($this->_related[$name])) {
			if($relation instanceof CLdapHasMany) {
				$this->_related[$name] = array();
			}
			//			else if($relation instanceof CStatRelation)
			//				$this->_related[$name]=$relation->defaultValue;
			else {
				$this->_related[$name] = null;
			}
		}

		if($params !== array()) {
			$results = $this->_related[$name];
			if($exists) {
				$this->_related[$name] = $save;
			}
			else {
				unset($this->_related[$name]);
			}
			return $results;
		}
		else {
			return $this->_related[$name];
		}
	}

	/**
	 * Returns a value indicating whether the named attribute is defined.
	 * @param string the relation name
	 * @return booolean a value indicating whether the named attribute is defined.
	 */
	public function hasAttribute($name)
	{
		$retval = isset($this->_attributes[$name]) || isset($this->_attributes[strtolower($name)]) || '*' == $this->_objectClasses;
		if (!$retval) {
			$aliases = CLdapSchema::getInstance()->getAttributeType($name)->getNames();
			foreach($aliases as $alias) {
				if ($name == $alias) continue;
				if (isset($this->_attributes[$alias]) || isset($this->_attributes[strtolower($alias)])) {
					$retval = true;
					break;
				}
			}
		}
		return $retval;
	}

	/**
	 * Returns a value indicating whether the named related object(s) is defined.
	 * @param string the relation name
	 * @return booolean a value indicating whether the named related object(s) is defined.
	 */
	public function hasRelation($name)
	{
		return isset($this->getMetaData()->relations[$name]);
	}

	/**
	 * Returns a value indicating whether the named related object(s) has been loaded.
	 * @param string the relation name
	 * @return booolean a value indicating whether the named related object(s) has been loaded.
	 */
	public function hasRelated($name)
	{
		return isset($this->_related[$name]) || array_key_exists($name, $this->_related);
	}

	public function setDn($dn) {
		$this->_dn = $dn;
	}

	/**
	 * Return the Dn
	 *
	 * @return string with Dn.
	 */
	public function getDn() {
		return $this->_dn;
	}

	/**
	 * Sets the branchDn for the model.
	 * @param string $dn the branchDn that this model is in.
	 */
	public function setBranchDn($dn) {
		$this->_branchDn = $dn;
	}

	/**
	 * Sets the overwrite mode for the model.
	 * @param boolean $dn the branchDn that this model is in.
	 */
	public function setOverwrite($ow) {
		$this->_overwrite = $ow;
	}

	/**
	 * Return all the attributes.
	 *
	 * @return array with attributes and their type definition.
	 */
	public function getLdapAttributes() {
		return $this->_attributes;
	}

	/**
	 * This method should be overridden to declare related objects.
	 *
	 * There are three types of relations that may exist between two active record objects:
	 * <ul>
	 * <li>BELONGS_TO: e.g. a member belongs to a team;</li>
	 * <li>HAS_ONE: e.g. a member has at most one profile;</li>
	 * <li>HAS_MANY: e.g. a team has many members;</li>
	 * </ul>
	 *
	 * Each kind of related objects is defined in this method as an array with the following elements:
	 * <pre>
	 * 'varName'=>array('relationType', 'own_attribute', 'className', 'foreign_attribute', ...additional options)
	 * </pre>
	 * where 'varName' refers to the name of the variable/property that the related object(s) can
	 * be accessed through; 'relationType' refers to the type of the relation, which can be one of the
	 * following four constants: self::BELONGS_TO, self::HAS_ONE and self::HAS_MANY;
	 * 'own_attribute' is the name of the attribute in the base object, if set to 'dn' 'foreign_attribute' can be set
	 * to a php statement that returns the DN (see example below);
	 * 'className' refers to the name of the ldap record class that the related object(s) is of;
	 * and 'foreign_attribute' is the name of the attribute in the related object(s).
	 *
	 * Additional options may be specified as name-value pairs in the rest array elements:
	 * <ul>
	 * <li>'<attributename>': string, definition of an item for a possible filter</li>
	 * </ul>
	 *
	 * Below is an example declaring related objects for 'Post' active record class:
	 * <pre>
	 * return array(
	 *     'address'=>array(self::BELONGS_TO, 'addressUID', 'LdapAddress', 'uid'),
	 *     'disks' => array(self::HAS_MANY, 'dn', 'LdapDisk', '$model->getDn()', array('sstDisk' => '*')),
	 * );
	 * </pre>
	 *
	 * @return array list of related object declarations. Defaults to empty array.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * Returns the static model of the specified Ldap class.
	 *
	 * @param string $className active record class name.
	 * @return CLdapRecord ldap record model instance.
	 */
	public static function model($className=__CLASS__)
	{
		$model = new $className(null);
		$model->_md = new CLdapRecordMetaData($model);
		$model->attachBehaviors($model->behaviors());

		return $model;
	}

	/**
	 * Returns the meta-data for this ldap record
	 * @return CLdapRecordMetaData the meta for this ldap record class.
	 */
	public function getMetaData()
	{
		if($this->_md !== null) {
			return $this->_md;
		}
		else {
			return $this->_md = self::model(get_class($this))->_md;
		}
	}

	public function findSubTree($criteria) {
		if (!isset($criteria['branchDn'])) {
			$criteria['branchDn'] = $this->_branchDn;
		}
		$class = get_class($this);
		if ('LdapSubTree' != $class && !is_subclass_of($class, 'LdapSubTree')) {
			throw new CLdapException(Yii::t('LdapComponent.record', 'findSubTree failt: used class is not type or subtype of \'LdapSubTree\'!'),
				 0x100002);
		}
		$server = CLdapServer::getInstance();
		$entries = $server->findSubTree($this, $criteria);
		//echo '<pre>' . print_r($entries, true) . '</pre>';
		$branchDn = '';
		$nodes = array();
		$retval = array();
		for ($i=0; $i<$entries['count']; $i++) {
			$objclasses = array();
			$item = new $class();
			for ($j=0; $j<$entries[$i]['count']; $j++) {
				if ('objectclass' == $entries[$i][$j]) {
					$attr = $entries[$i][$j];
					for ($k=0; $k<$entries[$i][$attr]['count']; $k++) {
						$objclasses[] = $entries[$i][$attr][$k];
					}
					continue;
				}
				$attr = $entries[$i][$j];
				for ($k=0; $k<$entries[$i][$attr]['count']; $k++) {
					$item->$attr = $entries[$i][$attr][$k];
				}
			}
			$item->_objectClasses = $objclasses;
			$item->_dn = $entries[$i]['dn'];
			$item->_readDn = $item->_dn;
			if (0 == $i) {
				$branchDn = $item->_dn;
			}
			$nodes[$item->_dn] = $item;
			if ($branchDn != $item->_dn) {
				$parentDn = substr($item->_dn, 1 + strpos($item->_dn, ','));
				if (isset($nodes[$parentDn])) {
					if (!isset($nodes[$parentDn]->__children)) {
						$nodes[$parentDn]->__children = array();
					}
					$nodes[$parentDn]->__children[] = $item;
				}
				else {
					throw new CLdapException(Yii::t('LdapComponent.record', 'findSubTree failt: parent \'{dn}\' not read!',
						array('{dn}'=>$parentDn)), 0x100001);
				}
			}
		}
		return $nodes[$branchDn];
	}


	public function findByAttributes($attributes)
	{
		$retval = $this->findAll($attributes);
		return 0 == count($retval) ? null : $retval[0];
	}

	public function findAll($criteria) {
		if (!isset($criteria['branchDn'])) {
			$criteria['branchDn'] = $this->_branchDn;
		}
		$class = get_class($this);
		$server = CLdapServer::getInstance();
		$entries = $server->findAll($this, $criteria);
		//echo '<pre>' . print_r($entries, true) . '</pre>';
		$retval = array();
		error_log('entries: ' . $entries['count']);
		for ($i=0; $i<$entries['count']; $i++) {
			$item = new $class();
			for ($j=0; $j<$entries[$i]['count']; $j++) {
				/* TODO: check if objectclasses are OK */
				if ('objectclass' == $entries[$i][$j]) {
					continue;
				}
				$attr = $entries[$i][$j];
				for ($k=0; $k<$entries[$i][$attr]['count']; $k++) {
					$item->$attr = $entries[$i][$attr][$k];
				}
			}
			$item->_dn = $entries[$i]['dn'];
			$item->_readDn = $item->_dn;
			$retval[] = $item;
		}
		if (isset($criteria['sort']) && '' != $criteria['sort']) {
			$sort = explode('.', $criteria['sort']);
			$fname = 'compare' . ucfirst($sort[0]);
			if (isset($sort[1])) {
				$fname .= ucfirst($sort[1]);
			}
			//echo "{$criteria['sort']}: $class->$fname<br/>";
			//echo '<pre>' . print_r($retval, true) . '</pre>';
			//try {
			$method = new ReflectionMethod($class, $fname);
			if ($method->isStatic()) {
				usort($retval, array($class, $fname));
			}
			//echo '<pre>' . print_r($retval, true) . '</pre>';
			//}
			//catch(ReflectionException $e) {

			//}
		}
		return $retval;
	}

	/**
	 * Saves the current record.
	 *
	 * The record is inserted as a node if its {@link isNewEntry}
	 * property is true (usually the case when the record is created using the 'new'
	 * operator). Otherwise, it will be used to update the corresponding node
	 * (usually the case if the record is obtained using one of those 'find' methods.)
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * If the record is saved via insertion, its {@link isNewEntry} property will be
	 * set false, and its {@link scenario} property will be set to be 'update'.
	 * And if its primary key is auto-incremental and is not set before insertion,
	 * the primary key will be populated with the automatically generated key value.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the saving succeeds
	 */
	public function save($runValidation=true, $attributes=null)
	{
		if(!$runValidation || $this->validate($attributes)) {
			return $this->isNewEntry() ? $this->insert($attributes) : $this->update($attributes);
		}
		else {
			return false;
		}
	}

	/**
	 * Inserts a node based on this ldap record attributes.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to Ldap successfully, its {@link isNewEntry} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws CLdapException if the record is not new
	 */
	public function insert($attributes=null)
	{
		if(!$this->isNewEntry()) {
			throw new CLdapException(Yii::t('LdapRecord.record', 'The entry cannot be inserted to LDAP because it is not new.'));
		}
		$server = CLdapServer::getInstance();
		return $server->add($this->createDn(), $this->createEntry(false));
	}

	/**
	 * Updates the node represented by this ldap record.
	 * All loaded attributes will be saved.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws CLdapException if the record is new
	 */
	public function update($attributes=null)
	{
		if($this->isNewEntry()) {
			throw new CLdapException(Yii::t('LdapRecord.record', 'The entry cannot be updated within LDAP because it is new.'));
		}
		$this->attributes = $attributes;
		$server = CLdapServer::getInstance();
		if ($this->_readDn == $this->createDn()) {
			$retval = $server->modify($this->_readDn, $this->createEntry(true));
		}
		else {
			$retval = $server->rename($this->_readDn, $this->createDnBase());
			if ($retval) {
				$retval = $server->modify($this->_dn, $this->createEntry(true));
			}
		}
		return $retval;
	}

	public function findByDn($dn) {
		$item = null;
		$server = CLdapServer::getInstance();
		$entry = $server->findByDn($dn);
		if (1 < $entry['count']) {
			throw new CLdapException(Yii::t('LdapRecord.record', 'Wrong result count ({count}) on findByDn'), array('{count}'=> $entry['count']));
		}
		else if (1 == $entry['count']) {
			$item = $this->model(get_class($this));
			for ($j=0; $j<$entry[0]['count']; $j++) {
				/* TODO: check if objectclasses are OK */
				if ('objectclass' == $entry[0][$j]) {
					continue;
				}
				$attr = $entry[0][$j];
				for ($k=0; $k<$entry[0][$attr]['count']; $k++) {
					$item->$attr = $entry[0][$attr][$k];
				}
			}
			$item->_dn = $entry[0]['dn'];
			$item->_readDn = $item->_dn;
		}
		return $item;
	}

	public function isNewEntry() {
		return is_null($this->_readDn);
	}

	public function getFilter($name) {
		return $this->_filter[$name];
	}

	public function hasObjectClass($objClassName) {
		return in_array($objClassName, $this->_objectClasses);
	}
	public function removeAttributesByObjectClass($objClassName) {
		if ($this->hasObjectClass($objClassName)) {
			$schema = CLdapSchema::getInstance();
			$objClass = $schema->getObjectClass($objClassName);
			if (null != $objClass) {
				foreach($objClass->getAttributes() as $name => $info) {
					unset($this->_attributes[$name]);
				}
				unset($this->_objectClasses[$objClassName]);
			}
		}
		else {
			throw new CLdapException(Yii::t('LdapRecord.record', 'Class "{class}" not found for removeAttributesByObjectClass', array('{class}', $class)));
		}
	}
	public function removeAttribute($names) {
		if (!is_array($names)) {
			$names = array($names);
		}
		foreach($names as $name) {
			if(isset($this->_attributes[$name])) {
				unset($this->_attributes[$name]);
			}
			else if(isset($this->_attributes[strtolower($name)])) {
				unset($this->_attributes[strtolower($name)]);
			}
		}
	}

	private function createDn() {
		$dn = '';
		foreach($this->_dnAttributes as $name) {
			//echo "Name: $name<br/>";
			if ('' != $dn) {
				$dn .= ',';
			}
			$dn .= $name . '=' . $this->$name;
			//echo "<pre>$name: $dn</pre>";
		}
		//echo 'DN: ' . $dn . '-' . $this->_branchDn . '-' . CLdapServer::getInstance()->getBaseDn() . '<br/>';
		$this->_dn = $dn . ',' . $this->_branchDn; // . ',' . CLdapServer::getInstance()->getBaseDn();
		return $this->_dn;
	}

	private function createDnBase() {
		$dn = '';
		foreach($this->_dnAttributes as $name) {
			if ('' != $dn) {
				$dn .= ',';
			}
			$dn .= $name . '=' . $this->$name;
			//echo "<pre>$name: $dn</pre>";
		}
		return $dn;
	}

	private function createEntry($isModify) {
		$entry = array();
		foreach($this->_attributes as $key => $value) {
			if ('dn' != $key && '' != $value['value']) {
				if (is_array($value['value'])) {
					foreach($value['value'] as $val) {
						$entry[$key][] = $val;
					}
				}
				else {
					$entry[$key][] = $value['value'];
				}
			}
		}
		if (!$isModify) {
			if (0 != count($this->_objectClasses)) {
				$entry['objectclass'] = array();
				foreach($this->_objectClasses as $class) {
					$entry['objectclass'][] = $class;
				}
			}
			else {
				throw new CLdapException(Yii::t('LdapRecord.record', 'Failt to createEntry for ldap_add. No objectClass(es) defined!'));
			}
		}
		return $entry;
	}

	// TODO: change name to createAttributeDefinitions
	protected function createAttributes() {
		$schema = CLdapSchema::getInstance();
		//$this->_attributes['dn'] = null;
		if ('*' == $this->_objectClasses) {
			return;
		}
		foreach($this->_objectClasses as $objClassName) {
			$objClass = $schema->getObjectClass($objClassName);
			if (null != $objClass) {
				if (is_null($this->_attributes)) {
					$this->_attributes = $objClass->getAttributes();
				}
				else {
					$this->_attributes = array_merge($this->_attributes, $objClass->getAttributes());
				}
				// TODO: is labeledURIObject also used in other LDAP servers than OpenLdap
			}
			if ('labeledURIObject' == $objClassName) {
				$this->_attributes['member'] = array('mandatory' => false, 'type' => 'array');
			}
		}
		//echo '<pre>' . print_r($this->_attributes, true) . '</pre>';
	}
}

/**
 * CLdapRecordMetaData represents the meta-data for aa Ldap Record class.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
class CLdapRecordMetaData
{
	public $relations = array();

	/**
	 * @var array list of relations
	 */
	private $_model;

	/**
	 * Constructor.
	 * @param CLdapRecord $model the model instance
	 */
	public function __construct($model)
	{
		$this->_model = $model;

		foreach($model->relations() as $name => $config) {
			$this->addRelation($name, $config);
		}
	}

	/**
	 * Adds a relation.
	 *
	 * $config is an array with three elements:
	 * relation type, own attribute, the related ldap record class and the foreign attribute.
	 *
	 * @throws CLdapException
	 * @param string $name Name of the relation.
	 * @param array $config Relation parameters.
	 * @return void
	 */
	public function addRelation($name, $config)
	{
		if(isset($config[0], $config[1], $config[2], $config[3])) {
			if (isset($config[4])) {
				$this->relations[$name] = new $config[0]($name, $config[1], $config[2], $config[3], $config[4]);
			}
			else {
				$this->relations[$name] = new $config[0]($name, $config[1], $config[2], $config[3]);
			}
		}
		else {
			throw new CLdapException(Yii::t('LdapRecord.record','Ldap record "{class}" has an invalid configuration for relation "{relation}". It must specify the relation type, the related active record class and the foreign key.', array('{class}'=>get_class($this->_model),'{relation}'=>$name)));
		}
	}
}

/**
 * CLdapBaseRelation is the base class for all ldap relations.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
abstract class CLdapBaseRelation extends CComponent {
	public $name;				// name of the relation
	public $attribute;			// own attribute
	public $className;			// foreign ldap record class
	public $foreignAttribute;	// foreign attribute
	public $options = array();

	/**
	 * Constructor.
	 * @param string name of the relation
	 * @param string name of the own attribute
	 * @param string name of the related ldap record class
	 * @param string name of the foreign attriubte for this relation
	 * @param array additional options (name=>value). The keys must be the property names of this class.
	 */
	public function __construct($name, $attribute, $className, $foreignAttribute, $options=array()) {
		$this->name = $name;
		$this->attribute = $attribute;
		$this->className = $className;
		$this->foreignAttribute = $foreignAttribute;
		$this->options = $options;
	}

	abstract public function createRelationalRecord($model);
}

/**
 * CLdapHasOne represents the parameters specifying a HAS_ONE relation.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
class CLdapHasOne extends CLdapBaseRelation {
	public function createRelationalRecord($model) {
		if ('dn' == $this->attribute) {
			$template = $this->foreignAttribute;
			eval("\$branchDn = $template;");
			//echo "branchDn: $branchDn" . '<br/>';
			$criteria = array();
			$criteria['branchDn'] = $branchDn;
			$criteria['attr'] = array();
		}
		else {
			$attr = $this->attribute;
			$criteria = array('attr' => array($this->foreignAttribute => $model->$attr));
		}
		foreach($this->options as $key => $value) {
			$criteria['attr'][$key] = $value;
		}
		//echo 'Criteria: <pre>' . print_r($criteria, true) . '</pre>';
		$results = CLdapRecord::model($this->className)->findAll($criteria);
		//echo 'Result: <pre>' . print_r($results, true) . '</pre>';
		if (0 == count($results)) {
			return null;
		}
		else if (1 < count($results)) {
			throw new CLdapException('Relation ' . __CLASS__ . ' between ');
		}
		return $results[0];
	}
}

/**
 * CLdapBelongsTo represents the parameters specifying a BELONGS_TO relation.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
class CLdapBelongsTo extends CLdapBaseRelation {
	public function createRelationalRecord($model) {
		//$attr = $this->attribute;
		//echo '<pre>' . print_r($attr, true) . '</pre>';
		//echo get_class($attr) . ': ' . $attr->getDn() . '<br/>';
		//echo get_class($model) . ': ' . $model->getDn() . '<br/>';
		//echo "foreignAttribute: $this->foreignAttribute" . '<br/>';
		$template = $this->foreignAttribute;
		eval("\$branchDn = $template;");
		//echo "branchDn: $branchDn" . '<br/>';
		$criteria = array();
		$criteria['branchDn'] = $branchDn;
		foreach($this->options as $key => $value) {
			$criteria['attr'][$key] = $value;
		}
		//echo '<pre>' . print_r($criteria, true) . '</pre>';
		$results = CLdapRecord::model($this->className)->findAll($criteria);
		//echo '<pre>' . print_r($results, true) . '</pre>';
		return $results;
	}
}

/**
 * CLdapHasOne represents the parameters specifying a HAS_MANY relation.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
class CLdapHasMany extends CLdapBaseRelation {
	public function createRelationalRecord($model) {
		if ('dn' == $this->attribute) {
			$template = $this->foreignAttribute;
			eval("\$branchDn = $template;");
			//echo "branchDn: $branchDn" . '<br/>';
			$criteria = array();
			$criteria['branchDn'] = $branchDn;
			$criteria['attr'] = array();
		}
		else {
			$attr = $this->attribute;
			$criteria = array('attr' => array($this->foreignAttribute => $model->$attr));
		}
		foreach($this->options as $key => $value) {
			$criteria['attr'][$key] = $value;
		}
		//echo '<pre>' . print_r($criteria, true) . '</pre>';
		$results = CLdapRecord::model($this->className)->findAll($criteria);
		//echo '<pre>' . print_r($results, true) . '</pre>';
		return $results;
	}
}
