<?
defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * This class holds a list of attribute values for an object. Why do we need a special class to do this? Because 
 * class can be retrieved by handle
 */
class AttributeValueList extends Object implements Iterator {
		
	private $attributes = array();
	
	public function addAttributeValue($ak, $value) {
		$this->attributes[$ak->getAttributeKeyHandle()] = $value;
	}
	
	public function __construct($array = false) {
		if (is_array($array)) {
			$this->attributes = $array;
		}
	}
	
	public function getAttribute($akHandle) {
		return $this->attributes[$akHandle];
	}
	
	public function rewind() {
		reset($this->attributes);
	}
	
	public function current() {
		return current($this->attributes);
	}
	
	public function key() {
		return key($this->attributes);
	}
	
	public function next() {
		next($this->attributes);
	}
	
	public function valid() {
		return $this->current() !== false;
	}
	
}


class AttributeValue extends Object {
	
	protected $attributeType;
	
	public static function getByID($avID) {
		$av = new AttributeValue();
		$av->load($avID);
		if ($av->getAttributeValueID() == $avID) {
			return $av;
		}
	}
	
	protected function load($avID) {
		$db = Loader::db();
		$row = $db->GetRow("select avID, uID, avDateAdded, atID from AttributeValues where avID = ?", array($avID));
		if (is_array($row) && $row['avID'] == $avID) {
			$this->setPropertiesFromArray($row);
		}

		$this->attributeType = $this->getAttributeTypeObject();
		$this->attributeType->controller->setAttributeKey($this->getAttributeKey());
		$this->attributeType->controller->setAttributeValue($this);
	}
	
	public function getValue() {
		return $this->attributeType->controller->getValue();		
	}
	
	public function delete() {
		$this->attributeType->controller->delete();

		$db = Loader::db();	
		$db->Execute('delete from AttributeValues where avID = ?', $this->getAttributeValueID());
	}
	
	public function getAttributeKey() {
		return $this->attributeKey;
	}
	public function setAttributeKey($ak) {
		$this->attributeKey = $ak;
	}
	public function getAttributeValueID() { return $this->avID;}
	public function getAttributeValueUserID() { return $this->uID;}
	public function getAttributeValueDateAdded() { return $this->avDateAdded;}
	public function getAttributeTypeID() { return $this->atID;}
	public function getAttributeTypeObject() {
		$ato = AttributeType::getByID($this->atID);
		return $ato;
	}
	
}