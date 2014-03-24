<?php

/**
 * Shopgate GmbH
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file AFL_license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interfaces@shopgate.com so we can send you a copy immediately.
 *
 * @author     Shopgate GmbH, Schloßstraße 10, 35510 Butzbach <interfaces@shopgate.com>
 * @copyright  Shopgate GmbH
 * @license    http://opensource.org/licenses/AFL-3.0 Academic Free License ("AFL"), in the version 3.0
 *
 * User: awesselburg
 * Date: 07.03.14
 * Time: 08:17
 *
 * File: Product.php
 *
 * @method                                      setUid(string $value)
 * @method string                               getUid()
 *
 * @method                                      setLastUpdate(string $value)
 * @method string                               getLastUpdate()
 *
 * @method                                      setName(string $value)
 * @method string                               getName()
 *
 * @method                                      setTaxPercent(float $value)
 * @method float                                getTaxPercent()
 *
 * @method                                      setTaxClass(string $value)
 * @method string                               getTaxClass()
 *
 * @method                                      setCurrency(string $value)
 * @method string                               getCurrency()
 *
 * @method                                      setDescription(string $value)
 * @method string                               getDescription()
 *
 * @method                                      setDeeplink(string $value)
 * @method string                               getDeeplink()
 *
 * @method                                      setPromotionSortOrder(int $value)
 * @method int                                  getPromotionSortOrder()
 *
 * @method                                      setInternalOrderInfo(string $value)
 * @method string                               getInternalOrderInfo()
 *
 * @method                                      setAgeRating(int $value)
 * @method int                                  getAgeRating()
 *
 * @method                                      setPrice(Shopgate_Model_Catalog_Price $value)
 * @method Shopgate_Model_Catalog_Price         getPrice()
 *
 * @method                                      setWeight(float $value)
 * @method float                                getWeight()
 *
 * @method                                      setWeightUnit(string $value)
 * @method string                               getWeightUnit()
 *
 * @method                                      setImages(array $value)
 * @method array                                getImages()
 *
 * @method                                      setCategories(array $value)
 * @method array                                getCategories()
 *
 * @method                                      setShipping(Shopgate_Model_Catalog_Shipping $value)
 * @method Shopgate_Model_Catalog_Shipping      getShipping()
 *
 * @method                                      setManufacturer(Shopgate_Model_Catalog_Manufacturer $value)
 * @method Shopgate_Model_Catalog_Manufacturer  getManufacturer()
 *
 * @method                                      setVisibility(Shopgate_Model_Catalog_Visibility $value)
 * @method Shopgate_Model_Catalog_Visibility    getVisibility()
 *
 * @method                                      setProperties(array $value)
 * @method array                                getProperties()
 *
 * @method                                      setStock(Shopgate_Model_Catalog_Stock $value)
 * @method Shopgate_Model_Catalog_Stock         getStock()
 *
 * @method                                      setIdentifiers(array $value)
 * @method array                                getIdentifiers()
 *
 * @method                                      setTags(array $value)
 * @method array                                getTags()
 *
 * @method                                      setRelations(array $value)
 * @method array                                getRelations()
 *
 * @method                                      setAttributeGroups(array $value)
 * @method array                                getAttributeGroups()
 *
 * @method                                      setAttributes(array $value)
 * @method array                                getAttributes()
 *
 * @method                                      setInputs(array $value)
 * @method array                                getInputs()
 *
 * @method                                      setAttachments(array $value)
 * @method array                                getAttachments()
 *
 * @method                                      setIsDefaultChild(bool $value)
 * @method bool                                 getIsDefaultChild()
 *
 * @method                                      setChildren(array $value)
 * @method array                                getChildren()
 *
 */

class Shopgate_Model_Catalog_Product
    extends Shopgate_Model_Abstract
{

    /**
     * define remove empty children nodes
     */
    const DEFAULT_CLEAN_CHILDREN_NODES = true;

    /**
     * define default item identifier
     */
    const DEFAULT_ITEM_IDENTIFIER = 'item';

    /**
     * weigh units
     */
    const DEFAULT_WEIGHT_UNIT_KG      = 'kg';
    const DEFAULT_WEIGHT_UNIT_OUNCE   = 'oz';
    const DEFAULT_WEIGHT_UNIT_GRAMM   = 'g';
    const DEFAULT_WEIGHT_UNIT_POUND   = 'lb';
    const DEFAULT_WEIGHT_UNIT_DEFAULT = self::DEFAULT_WEIGHT_UNIT_GRAMM;

    /**
     * tax
     */
    const DEFAULT_NO_TAXABLE_CLASS_NAME = 'no tax class';

    /** @var stdClass $_item */
    protected $_item;

    /** @var bool */
    protected $_isChild = false;

    /** @var array */
    protected $_children = array();

    /**
     * @var array
     */
    protected $_fireMethods
        = array(
            'setLastUpdate',
            'setUid',
            'setName',
            'setTaxPercent',
            'setTaxClass',
            'setCurrency',
            'setDescription',
            'setDeeplink',
            'setPromotionSortOrder',
            'setInternalOrderInfo',
            'setAgeRating',
            'setWeight',
            'setWeightUnit',

            'setPrice',
            'setShipping',
            'setManufacturer',
            'setVisibility',
            'setStock',
            'setImages',
            'setCategories',
            'setProperties',
            'setIdentifiers',
            'setTags',
            'setRelations',
            'setAttributeGroups',
            'setInputs',
            'setAttachments',
            'setChildren',
        );

    /**
     * init default object
     */
    public function __construct()
    {
        $this->setPrice(new Shopgate_Model_Catalog_Price());
        $this->setShipping(new Shopgate_Model_Catalog_Shipping());
        $this->setManufacturer(new Shopgate_Model_Catalog_Manufacturer());
        $this->setVisibility(new Shopgate_Model_Catalog_Visibility());
        $this->setStock(new Shopgate_Model_Catalog_Stock());

        $this->setInputs(array());
        $this->setChildren(array());
        $this->setAttributeGroups(array());
        $this->setRelations(array());
        $this->setTags(array());
        $this->setIdentifiers(array());
        $this->setProperties(array());
        $this->setCategories(array());
        $this->setImages(array());
        $this->setAttachments(array());
        $this->setAttributes(array());
    }

    /**
     * @param bool $isChild
     */
    public function setIsChild($isChild = true)
    {
        $this->_isChild = $isChild;
    }

    /**
     * @return bool
     */
    protected function _getIsChild()
    {
        return $this->_isChild;
    }

    /**
     * generate data dom object
     *
     * @return $this
     */
    public function generateData()
    {
        foreach ($this->_fireMethods as $method) {
            $this->{$method}();
        }

        return $this;
    }

    /**
     * generate xml result object
     *
     * @param Shopgate_Model_XmlResultObject $itemsNode
     *
     * @return Shopgate_Model_XmlResultObject
     */
    public function asXml(Shopgate_Model_XmlResultObject $itemsNode)
    {
        /**
         * global info
         *
         * @var $itemNode Shopgate_Model_XmlResultObject
         */
        $itemNode = $itemsNode->addChild(self::DEFAULT_ITEM_IDENTIFIER);

        $itemNode->addAttribute('uid', $this->getUid());
        $itemNode->addAttribute('last_update', $this->getLastUpdate());
        $itemNode->addChildWithCDATA('name', $this->getName());
        $itemNode->addChild('tax_percent', $this->getTaxPercent());
        $itemNode->addChild('tax_class', $this->getTaxClass());
        $itemNode->addChild('currency', $this->getCurrency());
        $itemNode->addChildWithCDATA('description', $this->getDescription());
        $itemNode->addChild('deeplink', $this->getDeeplink());
        $itemNode->addChild('promotion')->addAttribute('sort_order', $this->getPromotionSortOrder());
        $itemNode->addChildWithCDATA('internal_order_info', $this->getInternalOrderInfo());
        $itemNode->addChild('age_rating', $this->getAgeRating());
        $itemNode->addChild('weight', $this->getWeight())->addAttribute('unit', $this->getWeightUnit());

        /**
         * is default child
         */
        if($this->_getIsChild()) {
            $itemNode->addAttribute('default_child', $this->getIsDefaultChild());
        }

        /**
         * prices / trier prices
         */
        $this->getPrice()->asXml($itemNode);

        /**
         * images
         *
         * @var Shopgate_Model_XmlResultObject $imagesNode
         * @var Shopgate_Model_Media_Image     $imageItem
         */
        $imagesNode = $itemNode->addChild('images');
        foreach ($this->getImages() as $imageItem) {
            $imageItem->asXml($imagesNode);
        }

        /**
         * categories
         *
         * @var Shopgate_Model_XmlResultObject  $categoriesNode
         * @var Shopgate_Model_Catalog_Category $categoryItem
         */
        $categoriesNode = $itemNode->addChild('categories');
        foreach ($this->getCategories() as $categoryItem) {
            $categoryItem->asXml($categoriesNode);
        }

        /**
         * shipping
         */
        $this->getShipping()->asXml($itemNode);

        /**
         * manufacture
         */
        $this->getManufacturer()->asXml($itemNode);

        /**
         * visibility
         */
        $this->getVisibility()->asXml($itemNode);

        /**
         * properties
         *
         * @var Shopgate_Model_XmlResultObject  $propertiesNode
         * @var Shopgate_Model_Catalog_Property $propertyItem
         */
        $propertiesNode = $itemNode->addChild('properties');
        foreach ($this->getProperties() as $propertyItem) {
            $propertyItem->asXml($propertiesNode);
        }

        /**
         * stock
         */
        $this->getStock()->asXml($itemNode);

        /**
         * identifiers
         *
         * @var Shopgate_Model_XmlResultObject    $identifiersNode
         * @var Shopgate_Model_Catalog_Identifier $identifierItem
         */
        $identifiersNode = $itemNode->addChild('identifiers');
        foreach ($this->getIdentifiers() as $identifierItem) {
            $identifierItem->asXml($identifiersNode);
        }

        /**
         * tags
         *
         * @var Shopgate_Model_XmlResultObject $tagsNode
         * @var Shopgate_Model_Catalog_Tag     $tagItem
         */
        $tagsNode = $itemNode->addChild('tags');
        foreach ($this->getTags() as $tagItem) {
            $tagItem->asXml($tagsNode);
        }

        /**
         * relations
         *
         * @var Shopgate_Model_XmlResultObject  $relationsNode
         * @var Shopgate_Model_Catalog_Relation $relationItem
         */
        $relationsNode = $itemNode->addChild('relations');
        foreach ($this->getRelations() as $relationItem) {
            $relationItem->asXml($relationsNode);
        }

        /**
         * attribute / options
         *
         * @var Shopgate_Model_XmlResultObject         $attributeGroupsNode
         * @var Shopgate_Model_XmlResultObject         $attributesNode
         * @var Shopgate_Model_Catalog_Attribute       $attributeItem
         * @var Shopgate_Model_Catalog_AttributeGroup  $attributeGroupItem
         */
        if($this->_getIsChild()) {
            $attributesNode = $itemNode->addChild('attributes');
            foreach ($this->getAttributes() as $attributeItem) {
                $attributeItem->asXml($attributesNode);
            }
        } else {
            $attributeGroupsNode = $itemNode->addChild('attribute_groups');
            foreach ($this->getAttributeGroups() as $attributeGroupItem) {
                $attributeGroupItem->asXml($attributeGroupsNode);
            }
        }

        /**
         * inputs
         *
         * @var Shopgate_Model_XmlResultObject $inputsNode
         * @var Shopgate_Model_Catalog_Input   $inputItem
         */
        $inputsNode = $itemNode->addChild('inputs');
        foreach ($this->getInputs() as $inputItem) {
            $inputItem->asXml($inputsNode);
        }

        /**
         * attachments
         *
         * @var Shopgate_Model_XmlResultObject    $attachmentsNode
         * @var Shopgate_Model_Media_Attachment   $attachmentItem
         */
        $attachmentsNode = $itemNode->addChild('attachments');
        foreach ($this->getAttachments() as $attachmentItem) {
            $attachmentItem->asXml($attachmentsNode);
        }

        /**
         * children
         *
         * @var Shopgate_Model_XmlResultObject $childrenNode
         * @var object                         $itemNode ->children
         * @var Shopgate_Model_Catalog_Product $child
         * @var Shopgate_Model_XmlResultObject $childXml
         */
        if (!$this->_getIsChild()) {
            $childrenNode = $itemNode->addChild('children');
            foreach ($this->getChildren() as $child) {
                $child->asXml($childrenNode);
            }
            /**
             * remove empty nodes
             */
            if (self::DEFAULT_CLEAN_CHILDREN_NODES && count($this->getChildren()) > 0) {
                foreach ($itemNode->children as $childXml) {
                    $itemNode->replaceChild($this->_removeEmptyNodes($childXml), $itemNode->children);
                }
            }
        }

        return $itemsNode;
    }

    /**
     * add image
     *
     * @param Shopgate_Model_Media_Image $image
     */
    public function addImage(Shopgate_Model_Media_Image $image)
    {
        $images = $this->getImages();
        array_push($images, $image);
        $this->setImages($images);
    }

    /**
     * add category
     *
     * @param Shopgate_Model_Catalog_Category $category
     */
    public function addCategory(Shopgate_Model_Catalog_Category $category)
    {
        $categories = $this->getCategories();
        array_push($categories, $category);
        $this->setCategories($categories);
    }

    /**
     * add attribute group
     *
     * @param Shopgate_Model_Catalog_AttributeGroup $attributeGroup
     */
    public function addAttributeGroup($attributeGroup)
    {
        $attributesGroups = $this->getAttributeGroups();
        array_push($attributesGroups, $attributeGroup);
        $this->setAttributeGroups($attributesGroups);
    }

    /**
     * add property
     *
     * @param Shopgate_Model_Catalog_Property $property
     */
    public function addProperty($property)
    {
        $properties = $this->getProperties();
        array_push($properties, $property);
        $this->setProperties($properties);
    }

    /**
     * add attachment
     *
     * @param Shopgate_Model_Media_Attachment $attachment
     */
    public function addAttachment($attachment)
    {
        $attachments = $this->getAttachments();
        array_push($attachments, $attachment);
        $this->setAttachments($attachments);
    }

    /**
     * add identifier
     *
     * @param Shopgate_Model_Catalog_Identifier $identifier
     */
    public function addIdentifier($identifier)
    {
        $identifiers = $this->getIdentifiers();
        array_push($identifiers, $identifier);
        $this->setIdentifiers($identifiers);
    }

    /**
     * add tag
     *
     * @param Shopgate_Model_Catalog_Tag $tag
     */
    public function addTag($tag)
    {
        $tags = $this->getTags();
        array_push($tags, $tag);
        $this->setTags($tags);
    }

    /**
     * add relation
     *
     * @param Shopgate_Model_Catalog_Relation $relation
     */
    public function addRelation($relation)
    {
        $relations = $this->getRelations();
        array_push($relations, $relation);
        $this->setRelations($relations);
    }

    /**
     * add input
     *
     * @param Shopgate_Model_Catalog_Input $input
     */
    public function addInput($input)
    {
        $inputs = $this->getInputs();
        array_push($inputs, $input);
        $this->setInputs($inputs);
    }

    /**
     * add attribute option
     *
     * @param Shopgate_Model_Catalog_Attribute $attribute
     */
    public function addAttribute($attribute)
    {
        $attributes = $this->getAttributes();
        array_push($attributes, $attribute);
        $this->setAttributes($attributes);
    }

    /**
     * add child
     *
     * @param Shopgate_Model_Catalog_Product $child
     */
    public function addChild($child)
    {
        $children = $this->getChildren();
        array_push($children, $child);
        $this->setChildren($children);
    }

    /**
     * @param Shopgate_Model_XmlResultObject $childItem
     *
     * @return SimpleXMLElement
     */
    public function _removeEmptyNodes($childItem)
    {
        $output    = $childItem->asXML();
        $outputRef = $output;
        $output    = preg_replace('~<[^\\s>]+\\s*/>~si', null, $output);
        if ($output === $outputRef) {
            return new SimpleXMLElement($output);
        }

        return $this->_removeEmptyNodes(new Shopgate_Model_XmlResultObject($output));

    }

    /**
     * generate json result object
     *
     * @return array
     */
    public function asJson()
    {
        $result = array();

        /**
         * global
         */
        $result['uid']         = $this->getUid();
        $result['last_update'] = $this->getLastUpdate();
        $result['name']        = $this->getName();
        $result['tax_percent'] = $this->getTaxPercent();
        $result['tax_class']   = $this->getTaxClass();
        $result['currency']    = $this->getCurrency();
        $result['description'] = $this->getDescription();
        $result['deeplink']    = $this->getDeeplink();

        return $result;
    }

    /**
     * generate csv result object
     */
    public function asCsv()
    {

    }

    /**
     * @param $item
     *
     * @return $this
     */
    public function setItem($item)
    {
        $this->_item = $item;

        return $this;
    }
}