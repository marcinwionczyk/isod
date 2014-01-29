<?php
/**
 * LdapSubTree class file.
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/**
 * LdapSubTree
 *
 * LdapSubTree allows reading of a LDAP subtree.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */

class LdapSubTree extends CLdapRecord {
	protected $_objectClasses = '*';						// allow all object classes
	protected $__children = null;							// children of this node

	/**
	 * Returns the children of this node.
	 * @return Array list of children
	 */
	public function getChildren() {
		return $this->__children;
	}
}