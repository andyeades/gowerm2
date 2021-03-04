<?php
namespace Elevate\Reviews\Model;

class Xml
{

    public $assigned_parents;
    public $assigned_children;

    protected $match_assigned_qty = true;

    ///  protected $pro = "hidden";
    //  private $priv = "hidden too";

    //  public $func;
    //  public $notUsed;

    public function __construct() {
        //    $this->func = function() {
        //        return "Foo";
        //    };



    }

    public function addParent($parent_quote_item_id, $qty = 1) {


        //add the qty if already exists
        // $current_assigned_qty = $this->assigned_parents->products->$parent_quote_item_id->qty;
        // $new_qty = $current_assigned_qty + $qty;


        $newArr = new \stdClass(); //create a day object
        $newArr->qty = $qty; //add things to the day object
        $newArr->quote_item_id = $parent_quote_item_id;
        ///   {"assigned_parents":{"products":{"222":{"qty":1,"quote_item_id":"222"}}},"assigned_children":{}}
        ///

        $this->assigned_parents->products->$parent_quote_item_id = $newArr;

        //   print_r($this);
    }

    //match the parent qty to this
    public function adjustParentQty($parent_quote_item_id, $qty) {


        if(is_numeric($parent_quote_item_id)){
            $newArr = new stdClass(); //create a day object
            $newArr->qty = $qty; //add things to the day object
            $newArr->quote_item_id = $parent_quote_item_id;
            $this->assigned_parents->products->$parent_quote_item_id = $newArr;
        }
        ///   {"assigned_parents":{"products":{"222":{"qty":1,"quote_item_id":"222"}}},"assigned_children":{}}
        // 		$this->assigned_parents->products->$parent_quote_item_id = $newArr;

        //   print_r($this);
    }
    public function adjustChild($parent_quote_item_id, $qty) {


        $add_product = Mage::getModel('catalog/product')->load($additional_product);
        echo "ADJUST$qty|$parent_quote_item_id<br />";
        exit;
        //add the qty if already exists
        //  $current_assigned_qty = $this->assigned_parents->products->$parent_quote_item_id->qty;
        //  $new_qty = $current_assigned_qty + $qty;


        //    $newArr = new stdClass(); //create a day object
        //    $newArr->qty = $new_qty; //add things to the day object
        //    $newArr->quote_item_id = $parent_quote_item_id;
        ///   {"assigned_parents":{"products":{"222":{"qty":1,"quote_item_id":"222"}}},"assigned_children":{}}
        // 		$this->assigned_parents->products->$parent_quote_item_id = $newArr;

        //   print_r($this);
    }
    public function addChild($child_quote_item_id) {

        $newArr = new stdClass(); //create a day object
        $newArr->quote_item_id = $child_quote_item_id; //add things to the day object

        $this->assigned_children->products->$child_quote_item_id = $newArr;


    }
    public function removeChild($remove_id) {


        $newArr = new stdClass(); //create a day object
        $newArr->quote_item_id = $child_quote_item_id; //add things to the day object


        //remove_id
        unset($this->assigned_children->products->$remove_id);


    }
    public function getJson(){

        return json_encode($this);
    }
    public function getParentAssigns(){

        return json_encode($this);
    }
    public function getChildAssigns(){
        return json_encode($this);
    }

}