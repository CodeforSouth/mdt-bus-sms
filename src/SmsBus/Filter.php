<?php
/**
 * Filter functions for client input
 * 
 * @package SmsBus
 * @author Adrian Cardenas <arcardenas@gmail.com>
 */
namespace SmsBus;

use Zend\Filter\PregReplace;
use Zend\I18n\Filter\Alnum;
use Zend\I18n\Filter\Alpha;

class Filter 
{
    protected $dateFilter;
    protected $bodyFilter;
    protected $phoneFilter;
    protected $alphaFilter;
    protected $alnumFilter;

    public function __construct()
    {
        $this->dateFilter = new PregReplace(array('pattern' => '/[^0-9\-\/]/', 'replacement' => ''));
        $this->bodyFilter = new PregReplace(array('pattern' => '/[^a-zA-Z0-9_-\s\&\,]/', 'replacement' => ''));
        $this->phoneFilter = new PregReplace(array('pattern' => '/[^0-9\+\-]/', 'replacement' => ''));
        $this->alphaFilter = new Alpha(true);
        $this->alnumFilter = new Alnum(true);
    }

    /**
     * Filters & sanitizes the data received from a post quest.
     * @param array $data
     * @return array
     */
    public function sanitizePost(array $data)
    {
        $sanitized = array();
        $sanitized['AccountSid'] = $data['AccountSid'];
        $sanitized['ApiVersion'] = isset($data['ApiVersion']) ? substr($this->dateFilter->filter($data['ApiVersion']), 0, 10) : null;
        $sanitized['Body'] = isset($data['Body']) ? strtolower(substr($this->bodyFilter->filter($data['Body']), 0, 160)) : null;
        $sanitized['From'] = isset($data['From']) ? substr($this->phoneFilter->filter($data['From']), 0, 12) : null;
        $sanitized['FromCity'] = isset($data['FromCity']) ? substr($this->alphaFilter->filter($data['FromCity']), 0, 255) : null;
        $sanitized['FromCountry'] =isset($data['FromCountry']) ? substr($this->alphaFilter->filter($data['FromCountry']), 0, 4) : null;
        $sanitized['FromState'] = isset($data['FromState']) ? substr($this->alphaFilter->filter($data['FromState']), 0, 4) : null;
        $sanitized['FromZip'] = isset($data['FromZip']) ? substr($this->dateFilter->filter($data['FromZip']), 0, 10) : null;
        $sanitized['SmsMessageSid'] = isset($data['SmsMessageSid']) ? substr($this->alnumFilter->filter($data['SmsMessageSid']), 0, 34) : null;
        $sanitized['SmsSid'] = isset($data['SmsSid']) ? substr($this->alnumFilter->filter($data['SmsSid']), 0, 34) : null;
        $sanitized['SmsStatus'] = isset($data['SmsStatus']) ? substr($this->alphaFilter->filter($data['SmsStatus']), 0, 50) : null;
        $sanitized['To'] = isset($data['To']) ? substr($this->phoneFilter->filter($data['To']), 0, 12) : null;
        $sanitized['ToCity'] = isset($data['ToCity']) ? substr($this->alphaFilter->filter($data['ToCity']), 0, 255) : null;
        $sanitized['ToCountry'] =isset($data['ToCountry']) ? substr($this->alphaFilter->filter($data['ToCountry']), 0, 4) : null;
        $sanitized['ToState'] = isset($data['ToState']) ? substr($this->alphaFilter->filter($data['ToState']), 0, 4) : null;
        $sanitized['ToZip'] = isset($data['ToZip']) ? substr($this->dateFilter->filter($data['ToZip']), 0, 10) : null;

        return $sanitized;
    }
}