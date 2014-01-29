<?php
/**
 * LdapNameless class file.
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/**
 * LdapNameless
 *
 * LdapNameless allows reading one LDAP entry with unknown ObjectClass(es).
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */

class LdapNameless extends CLdapRecord {
	protected $_filter = array('all' => 'objectClass=*');	// defined filter(s)
	protected $_objectClasses = '*';						// allow all object classes
}