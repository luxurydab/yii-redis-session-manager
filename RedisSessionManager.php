<?php
class RedisSessionManager extends CHttpSession
{
	public $saveHandler = "files";
	protected $availableSaveHandlers = array('files', 'redis');
	public $useCustomStorage = false;

	public function init()
	{
		$this->setSaveHandler($this->saveHandler);
		parent::init();
	}

	/**
	 * @param string $value the current session save handler
	 * @throws CException if the handler is not string
	 */
	public function setSaveHandler($value)
	{
		if (in_array($value, $this->availableSaveHandlers)) {
			ini_set('session.save_handler', $value);
			$this->useCustomStorage = true;
		} else {
			throw new CException(Yii::t('yii', __CLASS__ . '.saveHandler "{handler}" is unknown.
             Available parameters for ' . __CLASS__ . '.saveHandler: ' . implode($this->availableSaveHandlers, ', '),
				array('{handler}' => $value)));
		}
	}

	/**
	 * @param string $value the current session save path
	 * @throws CException if the path is not string
	 */
	public function setSavePath($value)
	{
		if (is_string($value)) {
			session_save_path($value);
		} else {
			throw new CException(Yii::t('yii', __CLASS__ . '.savePath "{path}" is not string.',
				array('{path}' => $value)));
		}

	}

	/**
	 * Session open handler.
	 * Alias for open method
	 * @param string $savePath session save path
	 * @param string $sessionName session name
	 * @return boolean whether session is opened successfully
	 */
	public function openSession($savePath, $sessionName)
	{
		$this->open();
		if (session_id() !== '') {
			return true;
		}

	}

	/**
	 * Session close handler.
	 * Alias for close method
	 * @return boolean whether session is closed successfully
	 */
	public function closeSession()
	{
		$this->close();
		return true;
	}

	/**
	 * Session read handler.
	 * @param string $id session ID
	 * @return array the session data
	 */
	public function readSession($id)
	{
		return $_SESSION;
	}

	/**
	 * Session write handler.
	 * @param array $data
	 * @param string $id session ID
	 * @param string $data session data
	 * @return boolean whether session write is successful
	 */
	public function writeSession($id, $data)
	{
		$this->clear();
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->add($key, $value);
			}
		}
		if ($_SESSION) {
			return true;
		}
	}

	/**
	 * Session destroy handler.
	 * Alias for destroy method
	 * @param string $id session ID
	 * @return boolean whether session is destroyed successfully
	 */
	public function destroySession($id)
	{
		$this->destroy();
		if (session_id() == '') {
			return true;
		}
	}

	/**
	 * Session GC (garbage collection) handler.
	 * Alias for setTimeout method
	 * @param integer $maxLifetime the number of seconds after which data will be seen as 'garbage' and cleaned up.
	 * @return boolean whether session is GCed successfully
	 */
	public function gcSession($maxLifetime)
	{
		$this->setTimeout($maxLifetime);
		if ($this->getTimeout() == $maxLifetime) {
			return true;
		}
	}
}
