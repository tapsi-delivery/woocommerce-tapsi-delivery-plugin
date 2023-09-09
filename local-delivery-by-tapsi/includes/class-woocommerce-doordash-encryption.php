<?php

/**
 * Tapsi Encryption
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 */

/**
 * Tapsi Encryption
 *
 * Contains functionality encrypt and decrypt secret keys
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Doordash_Encryption {

	/**
	 * The cipher mode to use for encryption
	 *
	 * @var string
	 */
	protected $cipher = "aes-256-gcm";

	/**
	 * Encrypt sensitive data before storing in the database
	 *
	 * @param string $value The new, unserialized option value
	 * @param string $old_value The old option value
	 * @param string $option The option name (the dynamic part of the hook name)
	 * @return string Encrypted string
	 */
	public function encrypt_meta( $value, $old_value, $option ) {
		// Don't save obfuscated signing secrets
		if ( is_admin() && substr( $value, 0, 4 ) == '****' && ( $option == 'woocommerce_doordash_production_signing_secret' || $option == 'woocommerce_doordash_sandbox_signing_secret' ) ) {
			// If the value is full of stars, it's obfuscated. Ignore and return the old value for the option.
			$value = $old_value;
		}
		return $this->encrypt( $value );
	}

	/**
	 * Decrypt the signing secret when reading the option from the database
	 *
	 * @param string $value The value read from the database
	 * @param string $option Option name (dynamic part of the hook name)
	 * @return void
	 */
	public function decrypt_meta( $value, $option ) {
		// Decrypt the value
		$decrypted = $this->decrypt( $value );

		// Obfuscate the value of signing secrets
		if ( is_admin() && did_action( 'woocommerce_settings_woocommerce-doordash' ) && ( $option == 'woocommerce_doordash_production_signing_secret' || $option == 'woocommerce_doordash_sandbox_signing_secret' ) ) {
			// Fill it with stars, but only if we're on the settings page where it's being output
			$decrypted = str_pad( substr( $decrypted, -9 ), 32, '*', STR_PAD_LEFT );
		}

		// Return the decrypted value
		return $decrypted;
	}

	/**
	 * Decrypt the encrypted options when they are first retrieved
	 *
	 * @param array $alloptions Array of all site options
	 * @return array Filtered array with our values decrypted
	 */
	public function get_all_options( $alloptions ) {

		// The option names that need to be decrypted
		$decrypt = array(
			'woocommerce_doordash_production_signing_secret',
			'woocommerce_doordash_sandbox_signing_secret',
			'woocommerce_doordash_production_key_id',
			'woocommerce_doordash_sandbox_key_id',
		);

		// Loop through the option names
		foreach( $decrypt as $key ) {
			if ( array_key_exists( $key, $alloptions ) ) {
				// If the option exists, decrypt it and store back to the array
				$alloptions[$key] = $this->decrypt( $alloptions[$key] );
			}
		}

		// Return the options
		return $alloptions;
	}

	/**
	 * Encrypt data using AES-256-GCM
	 *
	 * @param string $value String to encrypt
	 * @return string Encrypted string
	 */
	public function encrypt( $data ) {
		// Get the key for encryption
		$key = $this->get_key();

		// Get the initialization vector
		$iv = $this->get_iv();

		// Encrypt the string
		$encrypted = openssl_encrypt( $data, $this->cipher, $key, 0, $iv, $tag );
		
		// Return encrypted string with initialization vector and tag
		return $encrypted . ':' . base64_encode( $iv ) . ':' . base64_encode( $tag );
	}
		
	/**
	 * Decrypt data from AES-256-GCM
	 *
	 * @param string $value String to decrypt
	 * @return string Decrypted string
	 */
	public function decrypt( $value ) {
		// Get the key for decryption
		$key = $this->get_key();

		// Get the encrypted string, initialization vector, and tag from the stored data
		list( $data, $iv, $tag ) = explode( ':', $value, 3 );

		// If there is no initialization vector or tag saved, we can't decrypt this, bail
		if ( empty( $iv ) || empty( $tag ) ) return false;

		// Decrypt and return the string
		return openssl_decrypt( $data, $this->cipher, $key, 0, base64_decode( $iv ), base64_decode( $tag ) );
	}

	/**
	 * Get the encryption key to use for encryption/decryption
	 *
	 * @return string Hashed key
	 */
	protected function get_key() {
		if ( defined( 'WCDD_ENCRYPTION_KEY' ) ) $key = WCDD_ENCRYPTION_KEY;
		else if ( defined( 'AUTH_KEY' ) ) $key = AUTH_KEY;
		else $key = 'O#F+ICSJ=kpy._W+@g{eEP;6G^S`.wAoOd_rMpsqj},eZ7g@k93j0e&;u4iG=nh,';

		return base64_decode( hash( 'sha256', $key ) );
	}

	/**
	 * Get the encryption initialization vector to use for encryption/decryption
	 *
	 * @return string IV of the correct length
	 */
	protected function get_iv() {
		// Return a randomized initialization vector of the length appropriate for the cipher
		return openssl_random_pseudo_bytes( openssl_cipher_iv_length( $this->cipher ) );
	}

	/**
	 * Dumb function to check if a key is already encrypted
	 *
	 * @param string $string String to check
	 * @return boolean Encrypted status
	 */
	public function is_encrypted( $string ) {
		// Our encryption function inserts : to separate encrypted data, iv, and tag
		// This isn't perfect, but the data we're encrypting shouldn't contain these.
		if ( str_contains( $string, ':' ) ) return true;
		return false;
	}
}