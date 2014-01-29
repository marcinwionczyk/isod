<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	private $_identity;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// username and password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Zapamiętaj mnie następnym razem',
			'username'=>'Użytkownik',
			'password'=>'Hasło'
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
			{
				switch($this->_identity->errorCode)
				{
					case UserIdentity::ERROR_UNKNOWN_IDENTITY:
						$this->addError('username', 'Nieprawidłowa nazwa użytkownika lub hasło');
						break;
					case UserIdentity::ERROR_USERNAME_INVALID:
						$this->addError('username', 'Nieprawidłowa nazwa użytkownika');
						break;
					case UserIdentity::ERROR_PASSWORD_INVALID:
						$this->addError('password', 'Hasło jest nieprawidłowe');
						break;
					default:
						break;
				}
			}
			
		}
	}
	public function login()
	{
		if($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else return false;
	}
}
