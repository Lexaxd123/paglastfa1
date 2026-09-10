<?php
namespace App\Controllers;

class Pages extends BaseController {
    public function index(){
        $data = array(
            'title' => 'Index Page',
            'name' => 'Joseph'
        );

        return view('partials/header', $data)
            .view('index', $data)
            .view('partials/footer');
    }

    public function about() {
        $data = array(
            'title' => 'About Page'
        );
        return view('partials/header', $data)
            .view('about')
            .view('partials/footer');
    }
}
?>