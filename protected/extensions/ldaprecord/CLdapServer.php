<?php
/**
 * CLdapServer class file.
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/*
 * TODO: Check communication with ldaps server
 * TODO: implement caching mechanism for objectclasses and attributetypes
 */

/**
 * CLdapServer
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
final class CLdapServer {
	/**
	 * @var CLdapServer static .
	 */
	private static $_instance = null;

	/**
	 * @var array with configuration params.
	 */
	private $_config = null;
	/**
	 * @var Ldap link identifier.
	 */
	private $_connection = null;
	/**
	 * @var
	 */
	private $_anonymous = false;

	/**
	 * Constructor private
	 *
	 * establish connection to Ldap server
	 */
	private function __construct() {
		$this->_config = array();
		$comp = Yii::app()->getComponent('ldap');
		$this->_config['server'] = $comp->server;
		$this->_config['port'] = $comp->port;
		$this->_config['base_dn'] = $comp->base_dn;
		if (isset($comp->bind_rdn)) {
			$this->_config['bind_rdn'] = $comp->bind_rdn;
		}
		if (isset($comp->bind_pwd)) {
			$this->_config['bind_pwd'] = $comp->bind_pwd;
		}
		if (null != $this->_config) {
			$this->_connection = @ldap_connect($this->_config['server'], $this->_config['port']) or die('LDAP connect failed!');
			if ($this->_connection === false) {
				throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_connect to {server} failt', array('{server}'=>$this->_config['server'])));
			}
			ldap_set_option($this->_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			if (isset($comp->bind_rdn) && isset($comp->bind_pwd)) {
				$ldapbind = @ldap_bind($this->_connection, $this->_config['bind_rdn'], $this->_config['bind_pwd']);
			}
			else {
				$this->_anonymous = true;
				$ldapbind = @ldap_bind($this->_connection);
			}
			if ($ldapbind === false) {
				throw new CLdapException(
				Yii::t('LdapComponent.server', 'ldap_bind failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
			}
		}
	}

	/**
	 * Get all defined objectclasses and attributetypes from LDAP server.
	 *
	 * @return array [objectclasses]=>CLdapObjectClass, [attributetypes]=>CLdapAttributeType
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function getDefinitions() {
		$result = @ldap_read($this->_connection,$this->_config['base_dn'],'objectClass=*',array('subschemaSubentry'),false,0,10,LDAP_DEREF_NEVER);
		if ($result === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_read failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$entries = @ldap_get_entries($this->_connection, $result);
		if ($entries === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_get_entries failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$entry = $entries[0];
		$subschemasubentry = $entry[$entry[0]][0];

		$result = @ldap_read($this->_connection, $subschemasubentry,'objectClass=*',array('objectclasses','attributetypes'),false,0,10,LDAP_DEREF_NEVER);
		if ($result === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_read failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$entries = @ldap_get_entries($this->_connection, $result);
		//echo '<pre>' . print_r($entries, true) . '</pre>';
		if ($entries === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_get_entries failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$objectclasses = array();
		$attributetypes = array();
		for($i=0; $i<$entries[0]['count']; $i++) {
			if ('objectclasses' == $entries[0][$i]) {
				$objclasses = $entries[0][$entries[0][$i]];
				for ($j=0; $j<$objclasses['count']; $j++) {
					$objclass = new CLdapObjectClass($objclasses[$j]);
					foreach($objclass->getNames() as $name) {
						$objectclasses[$name] = $objclass;
					}
				}
			}
			else if ('attributetypes' == $entries[0][$i]) {
				$attrtypes = $entries[0][$entries[0][$i]];
				for ($j=0; $j<$attrtypes['count']; $j++) {
					$attrtype = new CLdapAttributeType($attrtypes[$j]);
					foreach($attrtype->getNames() as $name) {
						$attributetypes[$name] = $attrtype;
					}
				}
			}
		}
		return array('objectclasses'=>$objectclasses, 'attributetypes'=>$attributetypes);
	}

	/**
	 * Return the base Dn from configuration.
	 *
	 * @return string with base Dn.
	 */
	public function getBaseDn() {
		return $this->_config['base_dn'];
	}

	/**
	 * Return if connection is anonymous.
	 *
	 * @return boolean is anonymous.
	 */
	public function isAnonymous() {
		return $this->_anonymous;
	}

	/**
	 * Finds all ldap records satisfying the specified condition (one level only.).
	 *
	 * @param CLdapRecord $model
	 * @param array $criteria
	 * @return array a complete result information in a multi-dimensional array on success and false on error.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function findAll($model, $criteria=array('attr'=>null)) {
		if (isset($criteria['attr'])) {
			if (!is_null($criteria['attr'])) {
				$filter = '(&';
				foreach ($criteria['attr'] as $key => $value) {
					if ('' != $value) {
						if ('*' == $value) {
							$filter .= "($key=*)";
						}
						else {
							$filter .= "($key=$value)";
						}
					}
				}
				$filter .= ')';
				if ('(&)' == $filter) {
					$filter = $model->getFilter('all');
				}
			}
		}
		else if (isset($criteria['filter'])) {
			$filter = $criteria['filter'];
		}
		else {
			throw new CLdapException(Yii::t('LdapComponent.server', 'findAll: neither attr nor filter set in criteria!'));
		}
		if (strpos($criteria['branchDn'], $this->_config['base_dn']) === false) {
			$branchDn = $criteria['branchDn'] . ',' . $this->_config['base_dn'];
		}
		else {
			$branchDn = $criteria['branchDn'];
		}
		error_log("findAll: branchDn: $branchDn<br/>");
		error_log("findAll: filter: $filter<br/>");
		$result = @ldap_list($this->_connection, $branchDn, $filter);
		if ($result === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_list failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		error_log('findAll: after ldap_list<br/>');
		$entries = @ldap_get_entries($this->_connection, $result);
		if ($entries === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_get_entries failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		error_log('findAll: after ldap_get_entries <br/>');
		return $entries;
	}

	/**
	 * Finds all ldap records satisfying the specified condition (subtree.).
	 *
	 * @param CLdapRecord $model
	 * @param array $criteria
	 * @return array a complete result information in a multi-dimensional array on success and false on error.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function findSubTree($model, $criteria) {
		$filter = '(&';
		if (isset($criteria['attr'])) {
			foreach ($criteria['attr'] as $key => $value) {
				if ('' != $value) {
					if ('*' == $value) {
						$filter .= "($key=*)";
					}
					else {
						$filter .= "($key=*$value*)";
					}
				}
			}
		}
		$filter .= ')';
		if ('(&)' == $filter) {
			$filter = $model->getFilter('all');
		}
		//echo "findAll: $filter<br/>";
		if (strpos($criteria['branchDn'], $this->_config['base_dn']) === false) {
			$branchDn = $criteria['branchDn'] . ',' . $this->_config['base_dn'];
		}
		else {
			$branchDn = $criteria['branchDn'];
		}
		$result = @ldap_search($this->_connection, $branchDn, $filter);
		if ($result === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_search failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$entries = @ldap_get_entries($this->_connection, $result);
		if ($entries === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_get_entries failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		return $entries;
	}

	/**
	 * Find one ldap record satisfying the Dn.
	 *
	 * @param string $dn
	 * @return array a complete result information in a multi-dimensional array on success and false on error.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function findByDn($dn) {
		if (strpos($dn, $this->_config['base_dn']) === false) {
			$dn = $dn . ',' . $this->_config['base_dn'];
		}
		$result = @ldap_read($this->_connection, $dn, '(objectclass=*)');
		if ($result === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_read failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		$entries = @ldap_get_entries($this->_connection, $result);
		if ($entries === false) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_get_entries failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}
		return $entries;
	}

	/**
	 * Modify an existing leaf with defined Dn.
	 *
	 * @param string $dn
	 * @param array all attributes as key->value
	 * @return boolean success.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function modify($dn, $entry) {
		//echo "dn: $dn<br/><pre>" . print_r($entry, true) . '</pre>';
		$retval = @ldap_modify($this->_connection, $dn, $entry);
		if (!$retval) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_modify failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}

		return true;
	}

	/**
	 * Add a new leaf with defined Dn.
	 *
	 * @param string $dn
	 * @param array all attributes as key->value
	 * @return boolean success.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function add($dn, $entry) {
		echo "dn: $dn<br/><pre>" . print_r($entry, true) . '</pre>';
		if (strpos($dn, $this->_config['base_dn']) === false) {
			$dn = $dn . ',' . $this->_config['base_dn'];
		}

		$retval = @ldap_add($this->_connection, $dn, $entry);
		if (!$retval) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_add failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}

		return true;
	}

	/**
	 * Change Dn.
	 * @param string $dn the old Dn.
	 * @param string $newDn the new Dn.
	 * @return boolean success.
	 * @throws CLdapException if the LDAP server generates an error.
	 */
	public function rename($dn, $newDn) {
		//$newDn .= ',' . $this->_config['base_dn'];
		//echo "dn: $dn $newDn</pre>";
		$retval = @ldap_rename($this->_connection, $dn, $newDn, null, false);
		if (!$retval) {
			throw new CLdapException(Yii::t('LdapComponent.server', 'ldap_rename failt ({errno}): {message}',
				array('{errno}'=>ldap_errno($this->_connection), '{message}'=>ldap_error($this->_connection))), ldap_errno($this->_connection));
		}

		return true;
	}

	public function close() {
		if (!is_null($this->_connection))
			ldap_unbind($this->_connection);
	}

	/**
	 * Static method which returns the singleton instance of this class.
	 *
	 * @return CLdapSchema
	 */
		public static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new CLdapServer();
		}
		return self::$_instance;
	}

	/**
	 * Is there an instance of this class?
	 *
	 * @return boolean whether the instance was created or not
	 */
	public static function hasInstance() {
		return !is_null(self::$_instance);
	}

	/**
	 * Don't allow cloning of this class from outside
	 */
	private function __clone() {}
}
