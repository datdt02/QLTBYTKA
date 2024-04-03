<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Page;

if(! function_exists('get_template')) {
    function get_template() {
        return array(
            'default'=>'Default',
            'home'=>'Homepage',
            'faqs'=>'Faqs',
            'contact'=>'Contact',
        );
    }
}