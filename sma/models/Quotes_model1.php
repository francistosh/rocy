<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

      public function getProductNames($term, $warehouse_id,$checkin_date,$rtype, $limit = 15)
    {
		
		$chckindate = date('Y-m-d',strtotime($checkin_date));
		if ($rtype=='resident'){
        $this->db->select('products.id, code, name, type, warehouses_products.quantity,products.category_id, IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion1,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(to_date,"%m-%d")), portion1qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion2,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate2,"%m-%d")),portion2qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion3,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate3,"%m-%d")),portion3qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion4,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate4,"%m-%d")),portion4qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion5,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate5,"%m-%d")),portion5qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion6,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate6,"%m-%d")),portion6qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion7,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate7,"%m-%d")),portion7qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion8,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate8,"%m-%d")),portion8qty,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion9,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate9,"%m-%d")),portion9qty,"1"))))))))) as price, tax_rate, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
	if ($term =="Tr" ) {
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') ");
         } else {
             $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND products.warehouse = '" . $warehouse_id . "'");        }
		}else if ($rtype=='nonresident'){
        $this->db->select('products.id, code, name, type, warehouses_products.quantity,products.category_id, IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion1,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(to_date,"%m-%d")), nrsdnt_amount,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion2,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate2,"%m-%d")),nrsdnt_amount2,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion3,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate3,"%m-%d")),nrsdnt_amount3,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion4,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate4,"%m-%d")),nrsdnt_amount4,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion5,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate5,"%m-%d")),nrsdnt_amount5,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion6,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate6,"%m-%d")),nrsdnt_amount6,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion7,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate7,"%m-%d")),nrsdnt_amount7,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion8,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate8,"%m-%d")),nrsdnt_amount8,IF((DATE_FORMAT("'.$chckindate.'","%m-%d")  >= DATE_FORMAT(portion9,"%m-%d") AND DATE_FORMAT("'.$chckindate.'","%m-%d") <= DATE_FORMAT(todate9,"%m-%d")),nrsdnt_amount9,"1"))))))))) as price, tax_rate, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        // if ($this->Settings->overselling) {
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND products.warehouse = '" . $warehouse_id . "'");
        // } else {
        //     $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
        //         . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        // }
		}
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getWHProduct($id)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('quote_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItemsWithDetails($quote_id)
    {
        $this->db->select('quote_items.id, quote_items.product_name, quote_items.product_code, quote_items.quantity, quote_items.serial_no, quote_items.tax, quote_items.unit_price, quote_items.val_tax, quote_items.discount_val, quote_items.gross_total, products.details,products.category_id');
        $this->db->join('products', 'products.id=quote_items.product_id', 'left');
        //$this->db->order_by('id', 'asc');
        $this->db->order_by('products.category_id', 'asc');
        $q = $this->db->get_where('quotes_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	 public function getPaxfromquote($quote_id)
    {
        $this->db->select('sum(quantity) as qty');
               //$this->db->order_by('id', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id,'product_code <>' => "%TLC%"));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }


    public function getQuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItems($quote_id)
    {
        $this->db->select('quote_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant,products.category_id')
            ->join('products', 'products.id=quote_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
            ->group_by('quote_items.id')
            ->order_by('products.category_id', 'asc')
            ->order_by('quote_items.product_name', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getAllReservationItems($quote_id)
    {
        $this->db->select('quote_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant,products.category_id')
            ->join('products', 'products.id=quote_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
            ->group_by('quote_items.id')
            ->order_by('products.category_id', 'asc')
			->where('products.category_id <>',10)
            ->order_by('quote_items.product_name', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function addQuote($data = array(), $items = array())
    {
        if ($this->db->insert('quotes', $data)) {
            $quote_id = $this->db->insert_id();
            if ($this->site->getReference('qu') == $data['reference_no']) {
                $this->site->updateReference('qu');
            }
            foreach ($items as $item) {
                $item['quote_id'] = $quote_id;
                $this->db->insert('quote_items', $item);
            }
            return true;
        }
        return false;
    }


    public function updateQuote($id, $data, $items = array())
    {
        if ($this->db->update('quotes', $data, array('id' => $id)) && $this->db->delete('quote_items', array('quote_id' => $id))) {
            foreach ($items as $item) {
                $item['quote_id'] = $id;
                $this->db->insert('quote_items', $item);
            }
            return true;
        }
        return false;
    }


    public function deleteQuote($id)
    {
        if ($this->db->delete('quote_items', array('quote_id' => $id)) && $this->db->delete('quotes', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->where('warehouses_products_variants.quantity >', 0)
            ->group_by('product_variants.id');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
