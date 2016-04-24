<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public $data = array(
		'act'		=> '',
		'action'	=> '',
		'parent'	=> 0,
		'h1' => ''
	);
	
	public function __construct()
	{
		parent::__construct();

		# load helpers
		$this->load->helpers('functions');
		
		# load models
		$this->load->model(
			array(
				'admin/settingsModel',
				'admin/adminModel',
				'admin/categoryModel',
				'admin/productModel',
				'admin/filterModel',
				'admin/filesModel',
				'admin/invoiceModel',
				'admin/supplierModel',
				'admin/sizeModel',
				'admin/unitModel',
				'admin/jstreeModel'
			)
		);
		
		# load library
		$this->load->library('my_imagemagic');
		
		# valid admin
		$this->adminModel->VALID_ADMIN();
		
		# settings
		$this->data['settings'] = $this->settingsModel->getSettings();
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// _VIEW
	private function _view($type = 'a_home', $data = array())
	{
		$this->load->view('admin/parts/a_header.php', $data);
		$this->load->view('admin/'.$type.'.php');
		$this->load->view('admin/parts/a_footer.php');
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// _CRUMBS	
	private function _crumbs($data = array(), $parent = 0)
	{
		static $j = 0;
		
		$crumbs = array();
		do{
			$terac = false;
			foreach ($data as  $item){
				foreach ($item as $i){
					if ($i->id == $parent){
						$crumbs[] = array('id'=>$i->id, 'name'=>$i->name);
						$parent = $i->parent;
						$terac = true;
						break;
					}
				}	
			}
			
			# предохранитель (избегаем зацыкливания)
			$j++;
			if ($j > 100000)return;
			# =====================================
			
		}while ($terac);

		return array_reverse($crumbs);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// URL (NEW)
	private function _getParents($parents, $not = 0, $parent_id = 1, $parent_name = '', $level = -1) 
	{
		$output = array();
		$level++;
		
		if (array_key_exists($parent_id, $parents)) {
			if ($parent_name != '') {
				$parent_name .= ' > ';
			}

			foreach ($parents[$parent_id] as $parent) {
				# избегаем зацыкливания
				if($parent->id == $not) continue;
				
				$output[$parent->id] = array(
					'id'	=> $parent->id,
					'name'	=>  $parent_name . $parent->nm,
					'level'	=> $level
				);
				
				$output += $this->_getParents($parents, $not, $parent->id, $parent_name . $parent->nm, $level);
			}
		}
		return $output;
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// LOGOUT	
	public function Logout()
	{
		session_destroy();
		redirect('/admin');
		exit;
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// INDEX
	public function Index()
	{
		$data = &$this->data;
		$data['h1'] = 'Склад';

		$this->_view('a_index', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CATEGORY
	public function Category()
{
		$data =& $this->data;
		
		$data['h1'] = 'Категории';
		
		if (isset($_POST['operation']))
		{
			$this->jstreeModel->set_settings(array(
				'structure_table'	=> 'category_struct', 
				'data_table'		=> 'category', 
				'data'				=> array('nm')
			));
			
			try {
				$rslt = null;
				
				switch ($_POST['operation'])
				{
					case 'get_node':
						$node = isset($_POST['id']) && $_POST['id'] !== '#' ? (int)$_POST['id'] : 0;
						$temp = $this->jstreeModel->get_children($node);
						
						$rslt = array();
						
						foreach ($temp as $v) {
							$rslt[] = array(
								'id' => $v['id'], 
								'text' => $v['nm'], 
								'children' => ($v['rgt'] - $v['lft'] > 1)
							);
						}
						break;
					case "get_content":
						$node = isset($_POST['id']) && $_POST['id'] !== '#' ? $_POST['id'] : 0;
						$node = explode(':', $node);
						if (count($node) > 1) {
							$rslt = array(
								'content' => 'Multiple selected'
							);
						}else{
							$temp = $this->jstreeModel->get_node((int)$node[0], array('with_path' => true));
							
							$rslt = array(
								'content' => 'Selected: /' . implode('/', array_map(function ($v) { return $v['nm']; }, $temp['path'])). '/'.$temp['nm']
							);
						}
						break;
					case 'create_node':
						$node		= isset($_POST['id']) && $_POST['id'] !== '#' ? (int)$_POST['id'] : 0;
						$position	= isset($_POST['position']) ? (int)$_POST['position'] : 0;
						$text		= isset($_POST['text']) ? $_POST['text'] : 'New node';
						$data		= array('nm' => $text);
						
						$temp = $this->jstreeModel->mk($node, $position, $data);
						
						$rslt = array('id' => $temp);
						
						break;
					case 'rename_node':
						$node = isset($_POST['id']) && $_POST['id'] !== '#' ? (int)$_POST['id'] : 0;
						$text = isset($_POST['text']) ? $_POST['text'] : 'Renamed node';
						$data = array('nm' => $text);
						
						$rslt = $this->jstreeModel->rn($node, $data);
						
						break;
					case 'delete_node':
						$node = isset($_POST['id']) && $_POST['id'] !== '#' ? (int)$_POST['id'] : 0;
						# проверка (есть ли вложенные категории и продукты)
						$result = $this->categoryModel->delCategory($node);
						if ($result){
							$rslt['error'] = $result;
						}else{
							$rslt = $this->jstreeModel->rm($node);
						}
						
						break;
					case 'move_node':
						$node 		= isset($_POST['id']) && $_POST['id'] !== '#' ? (int)$_POST['id'] : 0;
						$parn		= isset($_POST['parent']) && $_POST['parent'] !== '#' ? (int)$_POST['parent'] : 0;
						$position	= isset($_POST['position']) ? (int)$_POST['position'] : 0;
						
						$rslt = $this->jstreeModel->mv($node, $parn, $position);
						
						break;
					default:
						throw new Exception('Unsupported operation: ' . $_POST['operation']);
						break;
				}
				
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($rslt);
				exit;
				
			}catch (Exception $e) {
				header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
				header('Status:  500 Server Error');
				echo $e->getMessage();
			}
			
			exit;	
		}
	}
	/*
	public function Category()
	{
		$data = &$this->data;
		
		$data['path']	= '/admin/category/';
		$data['action'] = 'category';
		$data['act']    = 'all';
		$data['h1']		= 'Категории';
		
		$data['parent']	= isset($_GET['parent']) ? abs((int)$_GET['parent']) : 0;

		if (isset($_POST['add'])){
			$this->categoryModel->addCategory(); 
			redirect('/admin/category/?parent=' . (isset($_POST['parent']) ? $_POST['parent'] : 0) );
			exit;
		}
		if (isset($_POST['edit'])){
			$res = $this->categoryModel->updateCategory();
			redirect('/admin/category/?parent=' . (isset($_POST['parent']) ? $_POST['parent'] : 0));
			exit;
		}
		if (isset($_POST['category_order'])){
			$this->categoryModel->sortOrderCategory();
			exit;
		}
		
		
		if (isset($_GET['delete'])){
			$this->categoryModel->delCategory($_GET['delete']);
			redirect('/admin/category/?parent='.$data['parent']);
			exit;
		}
		
		if (isset($_GET['update'])){
			$data['category'] = $this->categoryModel->getCategory((int)$_GET['update']);
			if ( !$data['category']){redirect('/admin/category/?parent='.$data['parent']);exit;}

			$data['act']= 'update';
			$data['h1']	= 'Редактирование категории';
			
			$data['categories']	= $this->categoryModel->sortCategories($this->categoryModel->getCategories());
			$data['parents']	= $this->_getParents($data['categories'], $data['category']->id);
		}
		if (isset($_GET['add'])){
			$data['act']= 'add';
			$data['h1']	= 'Создание категории';
			
			$data['categories']	= $this->categoryModel->sortCategories($this->categoryModel->getCategories());
			$data['parents']	= $this->_getParents($data['categories']);
		}

		
		$data['categories']	= $this->categoryModel->sortCategories($this->categoryModel->getCategories());
		$data['crumbs']		= $this->_crumbs($data['categories'], $data['parent']);
		
		$this->_view('a_category', $data);
	}
	*/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// JSTREE
	public function JsTree()
	{
		$data =& $this->data;
		
		$data['h1'] = 'JSTree';
		
		if (isset($_GET['operation']))
		{
			/*
			$this->jstreeModel->set_settings(array(
				'structure_table'	=> 'tree_struct', 
				'data_table'		=> 'tree_data', 
				'data'				=> array('nm')
			));
			*/
			
			$this->jstreeModel->set_settings(array(
				'structure_table'	=> 'category_struct', 
				'data_table'		=> 'category', 
				'data'				=> array('nm')
			));
			
			try {
				$rslt = null;
				
				switch ($_GET['operation'])
				{
					case 'get_node':
						$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
						$temp = $this->jstreeModel->get_children($node);
						
						$rslt = array();
						
						foreach ($temp as $v) {
							$rslt[] = array(
								'id' => $v['id'], 
								'text' => $v['nm'], 
								'children' => ($v['rgt'] - $v['lft'] > 1)
							);
						}
						break;
					case "get_content":
						$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
						$node = explode(':', $node);
						if (count($node) > 1) {
							$rslt = array(
								'content' => 'Multiple selected'
							);
						}else{
							$temp = $this->jstreeModel->get_node((int)$node[0], array('with_path' => true));
							
							$rslt = array(
								'content' => 'Selected: /' . implode('/', array_map(function ($v) { return $v['nm']; }, $temp['path'])). '/'.$temp['nm']
							);
						}
						break;
					case 'create_node':
						$node		= isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
						$position	= isset($_GET['position']) ? (int)$_GET['position'] : 0;
						$text		= isset($_GET['text']) ? $_GET['text'] : 'New node';
						$data		= array('nm' => $text);
						
						$temp = $this->jstreeModel->mk($node, $position, $data);
						
						$rslt = array('id' => $temp);
						
						break;
					case 'rename_node':
						$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
						$text = isset($_GET['text']) ? $_GET['text'] : 'Renamed node';
						$data = array('nm' => $text);
						
						$rslt = $this->jstreeModel->rn($node, $data);
						
						break;
					case 'delete_node':
						$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
						
						$rslt = $this->jstreeModel->rm($node);
						
						break;
					case 'move_node':
						$node 		= isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
						$parn		= isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
						$position	= isset($_GET['position']) ? (int)$_GET['position'] : 0;
						
						$rslt = $this->jstreeModel->mv($node, $parn, $position);
						
						break;
					case 'analyze':
						var_dump($this->jstreeModel->analyze(true));
						die();
						break;
					default:
						throw new Exception('Unsupported operation: ' . $_GET['operation']);
						break;
				}
				
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($rslt);
				exit;
				
			}catch (Exception $e) {
				header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
				header('Status:  500 Server Error');
				echo $e->getMessage();
			}
			
			exit;	
		}
		
		$this->_view('a_jstree', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PRODUCTS
	public function Products()
	{
		$data = &$this->data;
		
		$data['path']	= '/admin/products/';
		$data['action']	= 'products';
		$data['act']	= 'all';
		$data['h1']		= 'Товары';
		
		$data['parent']	= isset($_GET['parent']) ? abs((int)$_GET['parent']) : 0;
		
		if (isset($_POST['getFormAddProduct'])){
			$data['category_id'] = isset($_POST['category_id']) ? abs((int)$_POST['category_id']) : 0;
			$data['categories']	= $this->categoryModel->getCategoryTree();
			$data['sizes']		= $this->sizeModel->getSizes();
			echo json_encode(
				array(
					'form_add_product' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/products/form-add-product.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['getFormEditProduct'])){
			$product_id = isset($_POST['product_id']) ? abs((int)$_POST['product_id']): 0;
			$data['product']	= $this->productModel->getProduct($product_id);
			$data['categories'] = $this->categoryModel->getCategoryTree();
			$data['sizes']		= $this->sizeModel->getSizes();
			echo json_encode(
				array(
					'form_edit_product' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/products/form-edit-product.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['get_products_of_categories'])){
			$data['products'] = $this->productModel->getProductsOfCategories($_POST['get_products_of_categories']);
			if (isset($_POST['format']) && $_POST['format'] == 'tr'){
				$data['products'] = preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/products/products-format-tr', $data, true));
			}
			
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array('products' => $data['products']));
			exit;
		}
		
		// ?
		if (isset($_POST['getProductOfCategory'])){
			$products = $this->productModel->getProducts($_POST['getProductOfCategory']);
			echo json_encode($products); 
			exit;
		}
	
		
		if (isset($_POST['product_order'])){
			$this->productModel->sortOrderProduct();
			exit;
		}

		if (isset($_POST['add_product'])){
			echo json_encode(
				$this->productModel->addProduct()
			);
			exit;
		}
		
		if (isset($_POST['update_product'])){
			echo json_encode(
				$this->productModel->updateProduct()
			);
			exit;
		}

		if (isset($_POST['delete_product'])){
			echo json_encode(
				$this->productModel->delProduct($_POST['delete_product'])
			);
			exit;
		}
		
		
		$this->_view('a_products', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// INVOICE
	public function Invoice($type = '')
	{
		$data = &$this->data;

		$data['path']		= '/admin/invoice/';
		$data['action']   	= 'invoice';
		$data['act']      	= 'all';
		$data['h1']     	= 'Накладная';
		
		$data['type'] = array('buy', 'sale');
		
		if (isset($_POST['getInvoices'])){
			$data['invoices'] = $this->invoiceModel->gettInvoices();
			 echo json_encode(
				array(
					'invoices' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/invoice/invoices-format-tr.php', $data, true))
				)
			);
		}
		
		if (isset($_POST['getFormAddInvoice'])){
			echo json_encode(
				array(
					'form_add_invoice' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/invoice/form-add-invoice.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['getFormEditInvoice'])){
			$invoice_id = isset($_POST['invoice_id']) ? abs((int)$_POST['invoice_id']): 0;
			
			$data['invoice'] = $this->invoiceModel->getProduct($invoice_id);
			echo json_encode(
				array(
					'form_edit_invoice' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/invoice/form-edit-invoice.php', $data, true))
				)
			);
			exit;
		}
		
		
		if (isset($_POST['add_invoice'])){
			echo json_encode(
				$this->invoiceModel->addInvoice()
			);
			exit;
		}
		
		if (isset($_POST['update_invoice'])){
			echo json_encode(
				$this->invoiceModel->updateInvoice()
			);
			exit;
		}

		if (isset($_POST['delete_invoice'])){
			echo json_encode(
				$this->invoiceModel->delInvoice($_POST['delete_invoice'])
			);
			exit;
		}
		
		
		$this->_view('a_invoices', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SUPPLIERS
	public function Supplier()
	{
		$data = &$this->data;

		$data['path']		= '/admin/supplier/';
		$data['action']   	= 'supplier';
		$data['act']      	= 'all';
		$data['h1']     	= 'Поставщики';
		
		if (isset($_POST['getSuppliers'])){
			$data['suppliers'] = $this->supplierModel->getSuppliers();
			echo json_encode(
				array('suppliers' => $this->load->view('/admin/service/suppliers-format-tr.php', $data, true))
			);
			exit;
		}
		
		if (isset($_POST['getSupplier'])){
			$data['supplier'] = $this->supplierModel->getSupplier($_POST['getSupplier']);
			echo json_encode(
				array('supplier' => $this->load->view('/admin/service/supplier/supplier.php', $data, true))
			);
			exit;
		}
		
		if (isset($_POST['getFormAddSupplier'])){
			echo json_encode(
				array(
					'form_add_supplier' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/supplier/form-add-supplier.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['getFormEditSupplier'])){
			$supplier_id = isset($_POST['supplier_id']) ? abs((int)$_POST['supplier_id']): 0;
			
			$data['supplier'] = $this->supplierModel->getSupplier($supplier_id);
			echo json_encode(
				array(
					'form_edit_supplier' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/supplier/form-edit-supplier.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['supplier_order'])){
			echo json_encode(
				$this->supplierModel->sortOrderSuppliers()
			);
			exit;
		}
		
		if (isset($_POST['add_supplier'])){
			echo json_encode(
				$this->supplierModel->addSupplier()
			);
			exit;
		}
		
		if (isset($_POST['update_supplier'])){
			echo json_encode(
				$this->supplierModel->updateSupplier()
			);
			exit;
		}

		if (isset($_POST['delete_supplier'])){
			echo json_encode(
				$this->supplierModel->delSupplier($_POST['delete_supplier'])
			);
			exit;
		}
		
		
		$data['suppliers'] = $this->supplierModel->getSuppliers();
		$data['suppliers'] = $this->load->view('/admin/service/supplier/suppliers-format-tr.php', $data, true);
		
		$this->_view('a_supplier', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SIZE
	public function Size()
	{
		$data = &$this->data;
		
		$data['path']		= '/admin/size/';
		$data['action']   	= 'size';
		$data['act']      	= 'all';
		$data['h1']     	= 'Размеры';
		
		if (isset($_POST['getFormAddSize'])){
			echo json_encode(
				array(
					'form_add_size' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/size/form-add-size.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['getFormEditSize'])){
			$size_id = isset($_POST['size_id']) ? abs((int)$_POST['size_id']): 0;
			
			$data['size'] = $this->sizeModel->getSize($size_id);
			echo json_encode(
				array(
					'form_edit_size' => preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/size/form-edit-size.php', $data, true))
				)
			);
			exit;
		}
		
		if (isset($_POST['getSizes'])){
			$data['sizes'] = $this->sizeModel->getSizes();
			$data['sizes'] = preg_replace('/\s+/u', ' ', $this->load->view('/admin/service/size/size-format-tr', $data, true));
			echo json_encode(array('sizes' => $data['sizes']));
			exit;
		}
		
		
		if (isset($_POST['size_order'])){
			$this->sizeModel->setOrderSize();
			exit;
		}
		
		if (isset($_POST['update_size'])){
			echo json_encode(
				$this->sizeModel->updateSize()
			);
			exit;
		}
		
		if (isset($_POST['add_size'])){
			echo json_encode(
				$this->sizeModel->addSize()
			);
			exit;
		}

		if (isset($_POST['delete_size'])){
			echo json_encode(
				$this->sizeModel->deleteSize($_POST['delete_size'])
			);
			exit;
		}
		
		$data['sizes'] = $this->sizeModel->getSizes();
		$data['sizes'] = $this->load->view('/admin/service/size/size-format-tr', $data, true);
		

		$this->_view('a_size', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// FILTER
	public function Filter()
	{
		$data = &$this->data;
		
		$data['parent']		= isset($_GET['parent']) ? abs((int)$_GET['parent']) : 0;
		$data['path']		= '/admin/filter/';
		$data['action']   	= 'filter';
		$data['act']      	= 'all';
		$data['h1']     	= 'Фильтры';


		if ( isset($_POST['filter_order']) ){
			$this->filterModel->setOrderFilter();
			exit;
		}
		if ( isset($_POST['add']) ){
			$this->filterModel->addFilter(); 
			redirect($data['path']);
			exit;
		}
		if ( isset($_POST['edit']) ){
			$res = $this->filterModel->updateFilter();
			redirect($data['path']);
			exit;
		}

		
		
		if (isset($_GET['delete'])){
			$this->filterModel->deleteFilter($_GET['delete']);
			redirect($data['path'].'?parent=' . $data['parent']);
			exit;
		}
		
		
		if (isset($_GET['update'])){
			$data['filter'] = $this->filterModel->getFilter((int)$_GET['update']);
			if ( !$data['filter'] ){redirect($data['path'].'?parent='.$data['parent']);exit;}
			
			$data['act']		= 'update';
			$data['h1']			= 'Редактирование фильтра';

			$this->_view('a_filter', $data);
			return;
		}
		if (isset($_GET['add'])){
			$data['act']  = 'add';
			$data['h1']	= 'Создание фильтра';
			
			$this->_view('a_filter', $data);
			return;
		}
		
		$data['filters'] = $this->filterModel->getFilters();

		$this->_view('a_filter', $data);
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SETTINGS
	public function Settings()
	{
		$data = &$this->data;

		$data['path']		= '/admin/settings/';
		$data['action']   	= 'settings';
		$data['act']      	= 'all';
		$data['h1']     	= 'Настройки';
		
		# обновление данных
		if (isset($_POST['edit'])){
			$this->settingsModel->editSettings();
			exit;
		}

		$data['admin']		= $this->adminModel->getAdmin();

		$this->output->set_header("Cache-Control: no-store");
		$this->_view('a_settings', $data);
	}	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// FILE_MANAGER
	public function Filesmanager()
	{
		# Подключение самой модели
		$this->load->model('admin/filesModel');
		
		# Подключение стилей
		if (isset($_GET['css'])){$this->filesModel->getCss();}
		
		if (METHOD == 'POST'){
			# если нет $_POST['action']
			if(!isset($_POST['action'])){show_404();exit;}
			
			# если нет метода в $this->filesModel
			if(!in_array($_POST['action'], get_class_methods($this->filesModel))){show_404();exit;}
			
			$res = $this->filesModel->$_POST['action']();
			
			echo json_encode($res);
			exit;
			
		}else{
			show_404();
			exit;
		}
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// TEST
	public function Test()
	{	
		phpinfo();
	}
	
}