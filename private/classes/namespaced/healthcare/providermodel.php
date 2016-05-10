<?php

/**
 *
 */
namespace HealthCare;

/**
 * Provider Model
 * Handle the data layer for providers
 * @author Steven Myers (myers9s123@gmail.com)
 */
class ProviderModel {

    /**
     * @var string
     */
    private static $table = 'providers';

    /**
     * @var array
     */
    private static $services = array(
        'Diabetes Care'         => true,
        'Dialysis'              => true,
        'Medication Management' => true,
        'Outpatient Therapy'    => true,
        'Oxygen'                => true,
        'Physical Therapy'      => true,
        'Speech Therapy'        => true,
        'Wound Care'            => true,
    );

    /**
     * fetchAll
     * Retreive all provider records
     * @return array
     * @access public
     * @static
     */
    public static function fetchAll() {
        $query   = static::getConnection()->select('id', 'name', 'location', 'phone_number', 'provides')->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $query->get();

        if (!empty($results)) {
            foreach ($results as $index => $result) {
                if (!empty($result['provides'])) {
                    $results[$index]['provides'] = static::unserializeProvides($result['provides']);
                }
            }
        }

        return $results;
    }

    /**
     * fetch
     * Retreive a provider record by id
     * @param posint $id
     * @return array
     * @access public
     * @static
     */
    public static function fetch($id) {
        $id     = intval($id);
        $result = static::getConnection()->select('id', 'name', 'location', 'phone_number', 'provides')->setFetchMode(\PDO::FETCH_ASSOC)->find($id);

        if (!empty($result) && !empty($result['provides'])) {
            $result['provides'] = static::unserializeProvides($result['provides']);
        }

        return $result;
    }

    /**
     * create
     * Create a new provider record
     * @param array $params
     * @return posint Inserted id for newly created record
     * @access public
     * @static
     */
    public static function create($params) {
        if (!empty($params['provides'])) {
            $params['provides'] = static::serializeProvides($params['provides']);
        }
        return static::getConnection()->insert($params);
    }

    /**
     * update
     * Update an existing provider record
     * @param posint $id
     * @param array $params
     * @return object PDOStatement
     * @access public
     * @static
     */
    public static function update($id, $params) {
        $id = intval($id);

        if (!empty($params['provides'])) {
            $params['provides'] = static::serializeProvides($params['provides']);
        }

        return static::getConnection()->where('id', '=', $id)->update($params);
    }

    /**
     * delete
     * Remove an existing provider record by id
     * @param posint $id
     * @return object PDOStatement
     * @access public
     * @static
     */
    public static function delete($id) {
        $id = intval($id);
        return static::getConnection()->where('id', '=', $id)->delete();
    }

    /**
     * isValidForCreate
     * @param array $params
     * @return bool
     * @access public
     * @static
     */
    public static function isValidForCreate($params) {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        if (empty($params['name']) || strlen($params['name']) < 1 || strlen($params['name']) > 250) {
            return false;
        }
        if (empty($params['location']) || strlen($params['location']) < 1 || strlen($params['location']) > 250) {
            return false;
        }
        // Phone number is optional
        if (isset($params['phone_number']) && !static::isValidPhoneNumber($params['phone_number'])) {
            return false;
        }
        // Provides is optional
        if (isset($params['provides']) && !static::isValidServices($params['provides'])) {
            return false;
        }
        return true;
    }

    /**
     * isValidForUpdate
     * @param array $params
     * @return bool
     * @access public
     * @static
     */
    public static function isValidForUpdate($params) {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        if (isset($params['name']) && (strlen($params['name']) < 1 || strlen($params['name']) > 250)) {
            return false;
        }
        if (isset($params['location']) && (strlen($params['location']) < 1 || strlen($params['location']) > 250)) {
            return false;
        }
        if (isset($params['phone_number']) && !static::isValidPhoneNumber($params['phone_number'])) {
            return false;
        }
        if (isset($params['provides']) && !static::isValidServices($params['provides'])) {
            return false;
        }
        return true;
    }

    /**
     * isValidPhoneNumber
     * @param string $phone
     * @return bool
     * @access public
     * @static
     */
    public static function isValidPhoneNumber($phone) {
        return preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $phone);
    }

    /**
     * isValidServices
     * @param array $services
     * @return bool
     * @access public
     * @static
     */
    public static function isValidServices($services) {
        if (!is_array($services)) {
            return false;
        }

        $provides  = $services;
        $intersect = array_intersect_key(static::$services, array_flip($provides));

        // Contains duplicates or elements that don't match
        if (count($provides) != count($intersect)) {
            return false;
        }

        return true;
    }

    /**
     * serializeProvides
     * @param array $field
     * @return string of comma separated values
     * @access private
     * @static
     */
    private static function serializeProvides($field) {
        return implode(',', $field);
    }

    /**
     * unserializeProvides
     * @param string $field
     * @return array
     * @access private
     * @static
     */
    private static function unserializeProvides($field) {
        return explode(',', $field);
    }

    /**
     * getConnection
     * @return object
     * @access private
     * @static
     */
    private static function getConnection() {
        return \DBConnection::table(static::$table);
    }
}
