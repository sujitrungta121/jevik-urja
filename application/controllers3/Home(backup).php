<?php
require APPPATH.'controllers/MainController.php';
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MainController
{
    public function __construct(){
        parent::__construct(); 

    }
    public function index()
    {

      $currency_info = $this->currency_library->currency('currency');
        $this->data['currency_currency'] = $currency_info[0]->currency;
        $this->data['currency_symbol'] = $currency_info[0]->symbol;

        /** Actual cart total infos from Shopping Cart Class
        * from codeigniter library base
        * Variables: $this->cart->total(), $this->cart->total_items(),
        * example: $this->cart->total()
        */
        $this->data['cart_total'] = $this->cart->format_number(($this->cart->total()) * $this->data['currency_currency']);
      // /exit();
        //Most sell products array construct.
        $this->load->model("Products_model","pm");
        $most_sell_products=array();
        foreach ($this->pm->most_sell_products() as $most_sell) {
            $most_sell_products[] = array(
                   'id'  			=> $most_sell->id,
                   'name'			=> $most_sell->name,
                   'details'		=> $most_sell->details,
                   'type'			=> $most_sell->type,
                   'product_id'		=> $most_sell->product_id,
                   'rank'			=> $most_sell->rank,
                   'category_id'	=> $most_sell->category_id,
                   'price'			=> ''.$this->cart->format_number($most_sell->price * $this->data['currency_currency']),
                   'stock'			=> $most_sell->stock,
                   'image'			=> $most_sell->image,
                   'url'			=> $most_sell->url,
                   'description_id'	=> $most_sell->description_id,
                   'language_id'	=> $most_sell->language_id,
                   'meta_tags'		=> $most_sell->meta_tags,
                   'meta_keys'		=> $most_sell->meta_keys
               );
        }
       $this->data['most_sell_products'] = $most_sell_products;

        //Most popular products array construct.
       $most_popular=array();
        foreach ($this->pm->most_popular_products() as $most_popular_products) {
            $most_popular[] = array(
                   'id'  			=> $most_popular_products->id,
                   'name'			=> $most_popular_products->name,
                   'details'		=> $most_popular_products->details,
                   'type'			=> $most_popular_products->type,
                   'product_id'		=> $most_popular_products->product_id,
                   'rank'			=> $most_popular_products->rank,
                   'category_id'	=> $most_popular_products->category_id,
                   'price'			=> ''.$this->cart->format_number($most_popular_products->price * $this->data['currency_currency']),
                   'stock'			=> $most_popular_products->stock,
                   'image'			=> $most_popular_products->image,
                   'url'			=> $most_popular_products->url,
                   'description_id'	=> $most_popular_products->description_id,
                   'language_id'	=> $most_popular_products->language_id,
                   'meta_tags'		=> $most_popular_products->meta_tags,
                   'meta_keys'		=> $most_popular_products->meta_keys
               );
        }

        $this->data['most_popular_products'] = $most_popular;

        //Slider products array construct.
        $slider_product=array();
        foreach ($this->pm->slider_products() as $slider_products) {
            $slider_product[] = array(
                   'id'  			=> $slider_products->id,
                   'name'			=> $slider_products->name,
                   'details'		=> $slider_products->details,
                   'type'			=> $slider_products->type,
                   'product_id'		=> $slider_products->product_id,
                   'rank'			=> $slider_products->rank,
                   'category_id'	=> $slider_products->category_id,
                   'price'			=> $this->cart->format_number($slider_products->price * $this->data['currency_currency']),
                   'stock'			=> $slider_products->stock,
                   'image'			=> $slider_products->image,
                   'url'			=> $slider_products->url,
                   'description_id'	=> $slider_products->description_id,
                   'language_id'	=> $slider_products->language_id,
                   'meta_tags'		=> $slider_products->meta_tags,
                   'meta_keys'		=> $slider_products->meta_keys
               );
        }

        $this->data['slider_products'] = $slider_product;

        //Categories array
        $this->load->model("Categories_model","cm");
        $this->data['categories'] = $this->cm->get_cats();
        $this->load->view('home', $this->data);
    }
}
