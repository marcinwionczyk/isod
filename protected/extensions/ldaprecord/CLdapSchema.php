<?php
/**
 * CLdapSchema class file.
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/**
 * CLadpSchema
 *
 * CLdapSchema holds all the defined object classes and attribute definitions.
 *
 * The used design pattern is Singleton. To get the one and
 * only instance of this class call CLdapSchema::getInstance().
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
final class CLdapSchema {
	/**
	 * @var array of CLdapObjectClass.
	 */
	private $_objectClasses = array();
	/**
	 * @var array of CLdapAttributeType.
	 */
	private $_attributeTypes = array();
	/**
	 * @var CLdapSchema static .
	 */
	private static $_instance = null;

	/**
	 * private constructor
	 */
	private function __construct() {
		$server = CLdapServer::getInstance();
		$definitions = $server->getDefinitions();
		$this->_objectClasses = $definitions['objectclasses'];
		$this->_attributeTypes = $definitions['attributetypes'];
	}

	/**
	 * Returns the object class by name
	 *
	 * @param String $name the name of the object class
	 * @return CLdapObjectClass the object class or null if not found
	 */
	public function getObjectClass($name) {
		if (isset($this->_objectClasses[$name])) {
			return $this->_objectClasses[$name];
		}
		return null;
	}

	/**
	 * Returns the attribute type by name
	 *
	 * @param String $name the name of the attribute type
	 * @return CLdapAttributeType the attribute type or null if not found
	 */
	public function getAttributeType($name) {
		$name = strtolower($name);
		if (isset($this->_attributeTypes[$name])) {
			return $this->_attributeTypes[$name];
		}
		return null;
	}

	/**
	 * Static method which returns the singleton instance of this class.
	 *
	 * @return CLdapSchema
	 */
	public static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new CLdapSchema();
		}
		return self::$_instance;
	}

	/**
	 * Don't allow cloning of this class from outside
	 */
	private function __clone() {}
}
