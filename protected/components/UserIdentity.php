<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	private $id;
		
	public function getId()
	{
		return $this->id;
	}
	
	public function authenticate()
	{
		//
		$specialusers = array('admin'=>'5fe8a33a3e4db096c4a7a6568bab5320','edytor'=>'a11d2cb9e1a8bd4b078cc01d40ad7c16',
				'wykładowca'=>'a11d2cb9e1a8bd4b078cc01d40ad7c16');
		$record = User::model()->with('role')->find('username=:username OR username_alt=:username',
				array(':username'=>$this->username));
		if($record === null) $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
		else
		{
			if(array_key_exists($this->username, $specialusers))
			{
				if($specialusers[$this->username] == md5($this->password))
				{
					$this->id = $record->id;
					$this->setState('roles', $record->role->name);
					$this->errorCode = self::ERROR_NONE;
				}
				else $this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$options = Yii::app()->params['ldap'];
				$dc_string = $options['dc'];
				$connection = @ldap_connect($options['host']);
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
				if($connection)
				{
					$bind = @ldap_bind($connection, "uid={$record->username},{$dc_string}", $this->password);
					if($bind)
					{
						$this->id = $record->id;
						$this->setState('roles', $record->role->name);
						$this->errorCode = self::ERROR_NONE;
					}
					else {
						if ($this->errorCode != self::ERROR_UNKNOWN_IDENTITY) $this->errorCode = self::ERROR_PASSWORD_INVALID; 
					}
				}
			}
		}
		return !$this->errorCode;
	}
	
	 
}