<?php

namespace Service;

/**
* 
*/
class MailChimp
{
	private $__apikey;
	private $__listID;
	private $__username;
	private $subscriberEmail;
	private $subscriberFirstName;
	private $subscriberLastName;

	function __construct(array $config=array())
	{
		if (strlen($config['api_key']) < 0 || empty($config['api_key'])) {
			
			die("Service::MailChimp : No Api Key Provided");
			throw new Exception("Service::MailChimp : No Api Key Provided", 1);
		}
		else{

			$this->setApikey($config['api_key']);
		}

		if (strlen($config['list_id']) < 0 || empty($config['list_id'])) {
			
			die("Service::MailChimp : No List ID Provided");
			throw new Exception("Service::MailChimp : No List ID Provided", 1);
		}
		else{

			$this->setListID($config['list_id']);
		}

		if (strlen($config['username']) < 0 || empty($config['username'])) {
			
			die("Service::MailChimp : No Username Provided");
			throw new Exception("Service::MailChimp : No Username Provided", 1);
		}
		else{

			$this->setUsername($config['username']);
		}
	}

    /**
     * @param mixed $__apikey
     *
     * @return self
     */
    public function setApikey($__apikey)
    {
        $this->__apikey = $__apikey;

        return $this;
    }

    /**
     * @param mixed $__listID
     *
     * @return self
     */
    public function setListID($__listID)
    {
        $this->__listID = $__listID;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->__username;
    }

    /**
     * @param mixed $__username
     *
     * @return self
     */
    public function setUsername($__username)
    {
        $this->__username = $__username;

        return $this;
    }

    /**
     * @param mixed $subscriberEmail
     *
     * @return self
     */
    public function setSubscriberEmail($subscriberEmail)
    {
        $this->subscriberEmail = $subscriberEmail;

        return $this;
    }

    /**
     * @param mixed $subscriberFirstName
     *
     * @return self
     */
    public function setSubscriberFirstName($subscriberFirstName)
    {
        $this->subscriberFirstName = $subscriberFirstName;

        return $this;
    }

    /**
     * @param mixed $subscriberLastName
     *
     * @return self
     */
    public function setSubscriberLastName($subscriberLastName)
    {
        $this->subscriberLastName = $subscriberLastName;

        return $this;
    }

    public function send()
    {
    	$region = substr($this->getApikey(),strpos($this->getApikey(),'-')+1);
    	$endpoint = "https://".$region.".api.mailchimp.com/3.0/lists/".$this->getListID();

    	$payload = json_encode([
    		'email_address' => $this->getSubscriberEmail(),
    		'merge_fields' => [
    			'FNAME' => $this->getSubscriberFirstName(),
    			'LNAME' => $this->getSubscriberLastName()
    		]
    	]);

    	$ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_USERPWD, $this->getUsername().':'. $this->getApikey());
	    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);                                                                                                                 

	    $result = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

	    return json_encode($result);
    }
}
