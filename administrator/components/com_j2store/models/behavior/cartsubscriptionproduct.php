<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
// No direct access to this file
defined('_JEXEC') or die;
class J2StoreModelCartsBehaviorCartSubscriptionproduct extends F0FModelBehavior {


	public function onBeforeAddCartItem(&$model, $product, &$json) {
		$app = JFactory::getApplication();
		$product_helper = J2Store::product();
		$values = $app->input->getArray($_REQUEST);
		$errors = array();

		//run quantity check
		$quantity = $app->input->get('product_qty');

		if (isset($quantity )) {
			$quantity = $quantity;
		} else {
			$quantity = 1;
		}

		//get options
		//get the product options
		$options = $app->input->get('product_option', array(0), 'ARRAY');
		if (isset($options )) {
			$options =  array_filter($options );
		} else {
			$options = array();
		}

		//iterate through stored options for this product and validate
		foreach($product->product_options as $product_option) {
			//check option type should not be file
			if ($product_option->required && empty($options[$product_option->j2store_productoption_id])) {
				$errors['error']['option'][$product_option->j2store_productoption_id] = JText::sprintf('J2STORE_ADDTOCART_PRODUCT_OPTION_REQUIRED', JText::_($product_option->option_name));
			}
			if(!empty($options[$product_option->j2store_productoption_id])) {
				F0FModel::getTmpInstance('Options', 'J2StoreModel')->validateOptionRules($options[$product_option->j2store_productoption_id], $product_option, $errors);
			}
		}

		$cart = $model->getCart();
		if(!$errors && $cart->cart_type != 'wishlist') {
			//before validating, get the total quantity of this variant in the cart
			$cart_total_qty = $product_helper->getTotalCartQuantity($product->variants->j2store_variant_id);

			//validate minimum / maximum quantity
			$error = $product_helper->validateQuantityRestriction($product->variants, $cart_total_qty, $quantity);
			if(!empty($error)) {
				$errors['error']['stock'] = $error;
			}

			//validate inventory
			if($product_helper->check_stock_status($product->variants, $cart_total_qty+$quantity) === false) {
				if ( $product->variants->quantity > 0 ) {
					$errors['error']['stock'] = JText::sprintf ( 'J2STORE_LOW_STOCK_WITH_QUANTITY', $product->variants->quantity );
				}else{
					$errors['error']['stock'] = JText::_('J2STORE_OUT_OF_STOCK');
				}
			}
		}

		if(!$errors) {
			//all good. Add the product to cart
			// create cart object out of item properties
			$item = new JObject;
			$item->user_id     = JFactory::getUser()->id;
			$item->product_id  = (int) $product->j2store_product_id;
			$item->variant_id  = (int) $product->variants->j2store_variant_id;
			$item->product_qty = J2Store::utilities()->stock_qty($quantity);
			$item->product_options = base64_encode(serialize($options));
			$item->product_type = $product->product_type;
			$item->vendor_id   = isset($product->vendor_id) ? $product->vendor_id : '0';
			// onAfterCreateItemForAddToCart: plugin can add values to the item before it is being validated /added
			// once the extra field(s) have been set, they will get automatically saved

			$results = J2Store::plugin()->event("AfterCreateItemForAddToCart", array( $item, $values ) );

			foreach ($results as $result)
			{
				foreach($result as $key=>$value)
				{
					$item->set($key,$value);
				}
			}

			// no matter what, fire this validation plugin event for plugins that extend the checkout workflow
			$results = array();
			$results =  J2Store::plugin()->event( "BeforeAddToCart", array( $item, $values, $product, $product->product_options) );
			foreach($results as $result) {
				if (! empty ( $result['error'] )) {
					$errors['error']['general'] = $result['error'];
				}
			}

			// when there is some error from the plugin then the cart item should not be added
			if(!$errors){
				$before_check = $this->beforeAddingToCart($item, $model);
				if(isset($before_check['error'])){
					//adding to cart is failed
					$errors['success'] = 0;
					$errors['error']['general'] = $before_check['error'];
				} else {
					$cartTable = $model->addItem($item);
					if(is_array($cartTable) && isset($cartTable['error'])) {
						//adding to cart is failed
						$errors['success'] = 0;
						$errors['error']['general'] = $cartTable['error'];
					} else if($cartTable === false) {
						//adding to cart is failed
						$errors['success'] = 0;
					} else {
						//adding cart is successful
						$errors['success'] = 1;
						$errors['cart_id'] = $cartTable->j2store_cart_id;
					}
				}
			}

		}

		$json->result = $errors;

	}

	/**
	 * Before adding to cart check the item already exist in cart
	 * */
	function beforeAddingToCart($item, $model){
		$app = JFactory::getApplication();
		$cart = $model->getCart();
		$errors = array();
		if (!empty($cart) && !$app->isAdmin()) {
			$keynames = array();

			$keynames ['cart_id'] = $cart->j2store_cart_id;
			$keynames ['variant_id'] = $item->variant_id;
			$keynames ['product_options'] = $item->product_options;

			$table = F0FTable::getInstance('Cartitems', 'J2StoreTable');

			$item->cart_id = $cart->j2store_cart_id;
			$table->product_id = $item->product_id;
			$table->variant_id = $item->variant_id;
			$table->product_type = $item->product_type;
			$item_params = new JRegistry;
			if (isset($item->cartitem_params)) {
				if (is_array($item->cartitem_params)) {
					$item_params->loadArray($item->cartitem_params);
				} else {
					$item_params->loadString($item->cartitem_params);
				}
			}
			$item->cartitem_params = $item_params->toString('JSON');

			if ($table->load($keynames)) {
				$errors['error'] = JText::_('J2STORE_PRODUCT_SUBSCRIPTION_ALREADY_ADDED_IN_CART');
				return $errors;
			}
		}
		return $errors;
	}

	function AfterAddCartItem($model,$table){
	}

	public function onGetCartItems(&$model, &$item) {

		//sanity check
		if($item->product_type != 'subscriptionproduct') return;

		$product_helper = J2Store::product();
		//Options
		//print_r(base64_decode($item->product_options));
		if (isset($item->product_options)) {
			$options = unserialize(base64_decode($item->product_options));
		} else {
			$options = array();
		}

		$product = $product_helper->setId($item->product_id)->getProduct();
		$product_option_data = $product_helper->getOptionPrice($options, $product->j2store_product_id);

		$item->product_name = $product->product_name;
		$item->product_view_url = $product->product_view_url;
		$item->options = isset($product_option_data['option_data'])? $product_option_data['option_data']: array();
		$item->option_price = $product_option_data['option_price'];
		$item->weight = $item->weight + $product_option_data['option_weight'];
		
		$item->weight_total = ($item->weight ) * $item->product_qty;
		$group_id = '';
		if(isset($item->group_id) && !empty($item->group_id)){
			$group_id = $item->group_id;
		}
		$item->pricing = $product_helper->getPrice($item, $item->product_qty,$group_id);

	}

	public function onValidateCart(&$model, $cartitem, $quantity) {

		//sanity check
		if($cartitem->product_type != 'subscriptionproduct') return;

		$product_helper = J2Store::product();
		$product = $product_helper->setId($cartitem->product_id)->getProduct();
		$variant = F0FModel::getTmpInstance('Variants', 'J2StoreModel')->getItem($cartitem->variant_id);
		$errors = array();

		//before validating, get the total quantity of this variant in the cart
		$cart_total_qty  = $product_helper->getTotalCartQuantity($variant->j2store_variant_id);


		//get the quantity difference. Because we are going to check the total quantity
		$difference_qty = $quantity - $cartitem->product_qty;

		//validate minimum / maximum quantity
		$error = $product_helper->validateQuantityRestriction($variant , $cart_total_qty, $difference_qty);
		if(!empty($error)) {
			$errors[] = $error;
		}

		//validate inventory
		if($product_helper->check_stock_status($variant, $cart_total_qty+$difference_qty) === false) {
			$errors[] = JText::_('J2STORE_OUT_OF_STOCK');
		}

		if(count($errors)) {
			throw new Exception(implode('/n', $errors));
			return false;
		}
		return true;
	}

}
