<?php

/**
 *
 */
namespace HealthCare;

/**
 * HealthCare Provider class
 * @author Steven Myers (myers9s123@gmail.com)
 */
class Provider {

    /**
     * @var posint
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $phone_number;

    /**
     * @var array
     */
    private $provides;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * @var
     */
    private static $field_setter_map = array(
        'name'         => 'setName',
        'location'     => 'setLocation',
        'phone_number' => 'setPhoneNumber',
        'provides'     => 'setServices',
    );

    /**
     * __construct
     * @param posint $id
     * @access public
     */
    public function __construct($id) {
        $this->id = $id;
        $this->initialize();
    }

    /**
     * Getter methods
     * @access public
     */

    /**
     * getId
     * @return posint
     * @access public
     */
    public function getId() {
        return $this->id;
    }

    /**
     * getName
     * @return string
     * @access public
     */
    public function getName() {
        return $this->name;
    }

    /**
     * getLocation
     * @return string
     * @access public
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * getPhoneNumber
     * @return string
     * @access public
     */
    public function getPhoneNumber() {
        return $this->phone_number;
    }

    /**
     * getProvides
     * @return array
     * @access public
     */
    public function getProvides() {
        return $this->provides;
    }

    /**
     * isEmpty
     * Is current provider record object empty
     * @return bool
     * @access public
     */
    public function isEmpty() {
        return empty($this->name);
    }

    /**
     * toArray
     * @return array
     * @access public
     */
    public function toArray() {
        return array(
            'id'           => $this->id,
            'name'         => $this->name,
            'location'     => $this->location,
            'phone_number' => $this->phone_number,
            'provides'     => $this->provides,
        );
    }

    /**
     * Mutator methods
     * @access public
     */

    /**
     * setName
     * @param string $name
     * @access public
     */
    public function setName($name) {
        $this->name = $name;
        $this->queryParams['name'] = $name;
    }

    /**
     * setLocation
     * @param string $location
     * @access public
     */
    public function setLocation($location) {
        $this->location = $location;
        $this->queryParams['location'] = $location;
    }

    /**
     * setPhoneNumber
     * @param string $phone
     * @access public
     */
    public function setPhoneNumber($phone) {
        $this->phone_number = $phone;
        $this->queryParams['phone_number'] = $phone;
    }

    /**
     * setProvides
     * @param array $services
     * @access public
     */
    public function setServices($services) {
        $this->provides = $services;
        $this->queryParams['provides'] = $services;
    }

    /**
     * setFromRaw
     * @param array $params
     * @access public
     */
    public function setFromRaw($params) {
        foreach (static::$field_setter_map as $field => $method) {
            if (isset($params[$field])) {
                $this->$method($params[$field]);
            }
        }
    }

    /**
     * save
     * @return bool
     * @access public
     */
    public function save() {
        if (empty($this->queryParams)) {
            return true;
        }
        $params = $this->queryParams;
        unset($this->queryParams);
        $result = ProviderModel::update($this->id, $params);
        return ($result->rowCount() > 0);
    }

    /**
     * Private methods
     * @access private
     */

    /**
     * initialize
     * @access private
     */
    private function initialize() {
        $data = ProviderModel::fetch($this->id);
        if (!empty($data)) {
            $this->name         = $data['name'];
            $this->location     = $data['location'];
            $this->phone_number = $data['phone_number'];
            $this->provides     = $data['provides'];
        }
    }
}
