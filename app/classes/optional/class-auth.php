<?php
/**
* Register the Auth class
* @todo	Set crypt_salt, crypt_iv, session_salt below
* @todo	Create a 'sessions' table in the database with the following columns:
*  - session_id (INT 11 auto_increment)
*  - user_id (INT 11)
*  - session (VARCHAR 8)
*  - client (ENUM 'mobile_web')
*  - created (DATETIME current_timestamp())
*  - used (DATETIME)
*  - destroyed (DATETIME)
* @todo	Create a login route that takes a session, marks it as used, encrypts the session, and drops it in a cookie
* @todo	Create a logout route that destroys the session and the cookie
* @todo	Remember to check sessions at the top of every route
*/
Flight::register('auth', 'Auth');
class Auth{

	const crypt_salt = ''; // 8 characters
	const crypt_iv = ''; // 16 characters
	const session_salt = ''; // 8 characters

    /**
     * Encrypt data
     *
     * @param	string	$data Data to encrypt
     * @return	string	Returns the encrypted data
     */ 
	public function encrypt($data){

		$d = openssl_encrypt($data, 'AES-128-CBC', self::crypt_salt, 0, self::crypt_iv);
		return rtrim(base64_encode($d), '=');

	}

    /**
     * Decrypt data
     *
     * @param	string	$data Data to decrypt
     * @return	string	Returns the decrypted data
     */ 
	public function decrypt($data){

		$d = base64_decode($data);
		return openssl_decrypt($d, 'AES-128-CBC', self::crypt_salt, 0, self::crypt_iv);

	}

    /**
     * Creates a session for a given $user_id and $client
     *
     * @param	int	    $user_id User id
     * @param	string	$client Client type
     * @return	string	Returns the session
     */ 
	public function create_session($user_id, $client = ''){

        // Never create a session when one already exists
        //$this->destroy_session($user_id, $client);

		$session = substr(str_shuffle(MD5(microtime())), 0, 8);

	    $q_session = Flight::db()->insert(
	    	"sessions",[
                "user_id" => $user_id,
                "client" => $client,
                "session" => $session
	    	]
	    );

        if($q_session->rowCount() > 0){
            return $session;
        }else{
            return false;
        }

	}

    /**
     * Mark a session as used
     *
     * @param	string	$session Session
     * @return	bool	Returns true if successful, false otherwise
     */ 
	public function use_session($session){

	    $q_session = Flight::db()->update(
	    	"sessions",
	    	["used" => Medoo::raw('NOW()')],
	    	["session" => $session]
	    );

	    if($q_session){
		    return true;
	    }else{
		    return false;
	    }

	}

    /**
     * Destroys all the sessions for a given user with an optional client type
     *
     * @param	int	    $user_id User id
     * @param	string	$client Client type
     * @return	bool	Returns true if successful, false otherwise
     */ 
	public function destroy_session($user_id, $client = null){

		if($client){
			$where = ["AND" => ["user_id" => $user_id, "client" => $client]];
		}else{
			$where = ["user_id" => $user_id];
		}

	    $q_session = Flight::db()->update(
	    	"sessions",
	    	["destroyed" => Medoo::raw('NOW()')],
	    	$where
	    );

	    if($q_session){
		    return true;
	    }else{
		    return false;
	    }

	}

    /**
     * Encrypts a session with the user_id and access level
     *
     * @param	string	$session Session
     * @param	int	    $user_id User id
     * @param	string	$access Access level
     * @return	string	Returns the encrypted session
     */ 
	public function encrypt_session($session, $user_id, $access){

        if(empty($session) || empty($user_id) || empty($access)){
            return false;
        }

		return $this->encrypt(self::session_salt.':'.$session.':'.$user_id.':'.$access);

	}

    /**
     * Decrypts a session
     *
     * @param	string	$session Session
     * @return	array	Returns the decrypted session info
     */ 
    public function decrypt_session($session){

		$x = explode(':', $this->decrypt($session));
        return [
            'session' => $x[1],
            'user_id' => $x[2],
            'access' => $x[3]
        ];

	}

    /**
     * Checks a session
     *
     * @param	string	$session Session, encrypted or not
     * @param	string	$access Access level (optional)
     * @return	array|bool	Returns the user_id & access | false if the session is invalid
     */ 
	public function check_session($session, $access = null){

		if(empty($session)){
			return false;
		}

		// If the session is encrypted
        if(strlen($session) > 8){

			$decrypted_session = $this->decrypt_session($session);

            // Lookup the user from the session
            $q_user = Flight::db()->get(
                "sessions",
                ["user_id","created","destroyed"],
                ["session" => $decrypted_session['session']]
            );

            $q_user['access'] = $decrypted_session['access'];

        // Not encrypted and we need to know the access level
        }else{

            $q_user = Flight::db()->get(
                "sessions",
                ["[>]users" => "user_id"],
                ["sessions.user_id","sessions.created","sessions.destroyed","users.access"],
                ["sessions.session" => $session]
            );

        }

	    // Check the session
	    if(!empty($q_user['user_id']) && empty($q_user['destroyed'])){

            $return = [
                'user_id' => $q_user['user_id'],
                'access' => $q_user['access'],
                'created' => $q_user['created']
            ];

            if(empty($access)){

                return $return;
                
            }else{

                // Check whether access is aloud
                if($this->access($access, $q_user['access'])){

                    return $return;

                }else{

                    return false;

                }

            }

	    }else{

			return false;

	    }
	}

    /**
     * Checks if the current user has access to a given access level
     *
     * @param	string	$aloud The access level aloud for this route
     * @param	string	$current The current users access level from the database
     * @return	bool	Returns true if the user has access, false otherwise
     */ 
	public function access($aloud, $current){

        if(empty($aloud) || empty($current)){
            return false;
        }

        $list = array('blocked','user','beta','kit','employee','admin');

		if(array_search($current, $list) >= array_search($aloud, $list)){
			return true;
		}else{
			return false;
		}

	}

}