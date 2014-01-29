<?php
/**
 * LdapComponent class file.
 *
 * @author: Christian Wittkowski <wittkowski@devroom.de>
 * @copyright: Copyright &copy; 2010-2011 Christian Wittkowski
 * @version: 0.4
 */

/**
 * LdapComponent
 *
 * LdapComponent is the base class for this extension.
 * Holds all the configuration items from config/main.php.
 *
 * @author Christian Wittkowski <wittkowski@devroom.de>
 * @version $Id: $
 * @package ext.ldaprecord
 * @since 0.4
 */
class LdapComponent extends CApplicationComponent {

	public $server;		// URL of the server
	public $port;		// Port to access server
	public $base_dn;	// start node
	public $bind_rdn;	// node of the user
	public $bind_pwd;	// password of the user
}